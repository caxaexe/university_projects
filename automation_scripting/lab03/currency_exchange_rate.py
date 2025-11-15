#!/usr/bin/env python3
import argparse
import json
import os
import sys
import logging
from datetime import datetime

DATA_DIR = "/app/data"
os.makedirs(DATA_DIR, exist_ok=True)

try:
    import requests
    HAS_REQUESTS = True
except Exception:
    import urllib.request as _urllib_request
    import urllib.parse as _urllib_parse
    HAS_REQUESTS = False

logger = logging.getLogger("currency_exchange")
logger.setLevel(logging.DEBUG)

file_handler = logging.FileHandler("error.log", encoding="utf-8")
file_handler.setLevel(logging.ERROR)
file_handler.setFormatter(logging.Formatter("%(asctime)s %(levelname)s: %(message)s"))
logger.addHandler(file_handler)

console_handler = logging.StreamHandler(sys.stderr)
console_handler.setLevel(logging.ERROR)
console_handler.setFormatter(logging.Formatter("%(levelname)s: %(message)s"))
logger.addHandler(console_handler)


def parse_args():
    p = argparse.ArgumentParser(description="Get currency exchange rate from API and save JSON")
    p.add_argument("from_currency", help="Currency to convert from (e.g. USD)")
    p.add_argument("to_currency", help="Currency to convert to (e.g. EUR)")
    p.add_argument("date", help="Date in format YYYY-MM-DD (between 2025-01-01 and 2025-09-15)")
    p.add_argument("--api-url", default=os.environ.get("API_URL", "http://php_service:80"), help="Base URL for the API (default: http://php_service:80)")
    p.add_argument("--api-key", default=os.environ.get("API_KEY", "EXAMPLE_API_KEY"), help="API key (or set env API_KEY)")
    return p.parse_args()


def validate_inputs(from_c, to_c, date_str):
    allowed = {"MDL", "USD", "EUR", "RON", "RUS", "UAH"}
    from_c = from_c.upper()
    to_c = to_c.upper()
    if from_c not in allowed or to_c not in allowed:
        raise ValueError(f"Unsupported currency. Allowed: {', '.join(sorted(allowed))}")
    try:
        d = datetime.strptime(date_str, "%Y-%m-%d")
    except ValueError:
        raise ValueError("Date must be in YYYY-MM-DD format.")
    start = datetime.strptime("2025-01-01", "%Y-%m-%d")
    end = datetime.strptime("2025-09-15", "%Y-%m-%d")
    if not (start <= d <= end):
        raise ValueError("Date out of available range: 2025-01-01 .. 2025-09-15.")
    return from_c, to_c, date_str


def make_request(api_url, from_c, to_c, date_str, api_key, timeout=10):
    api_url = api_url.rstrip("/")
    url = f"{api_url}/?from={from_c}&to={to_c}&date={date_str}"

    if HAS_REQUESTS:
        try:
            resp = requests.post(url, data={"key": api_key}, timeout=timeout)
            resp.raise_for_status()
            return resp.text, resp.headers.get("Content-Type", "")
        except Exception as e:
            raise ConnectionError(f"HTTP request failed: {e}")
    else:
        try:
            data = _urllib_parse.urlencode({"key": api_key}).encode("utf-8")
            req = _urllib_request.Request(url, data=data, method="POST")
            req.add_header("Content-Type", "application/x-www-form-urlencoded")
            with _urllib_request.urlopen(req, timeout=timeout) as resp:
                raw = resp.read()
                ctype = resp.getheader("Content-Type", "")
                return raw.decode("utf-8"), ctype
        except Exception as e:
            raise ConnectionError(f"HTTP request failed (urllib): {e}")


def save_json_to_file(data_obj, from_c, to_c, date_str):
    safe_name = f"{from_c}_to_{to_c}_{date_str}.json"
    path = os.path.join(DATA_DIR, safe_name)
    with open(path, "w", encoding="utf-8") as f:
        json.dump(data_obj, f, ensure_ascii=False, indent=2)
    print(f"Success: saved API response to {path}")
    return path


def main():
    args = parse_args()
    try:
        from_c, to_c, date_str = validate_inputs(args.from_currency, args.to_currency, args.date)
    except Exception as e:
        msg = f"Input error: {e}"
        print(msg, file=sys.stderr)
        logger.error(msg)
        sys.exit(2)

    try:
        raw_text, content_type = make_request(args.api_url, from_c, to_c, date_str, args.api_key)
    except Exception as e:
        msg = f"Network/API error: {e}"
        print(msg, file=sys.stderr)
        logger.exception(msg)
        sys.exit(1)

    try:
        parsed = json.loads(raw_text)
    except Exception as e:
        msg = f"Failed to parse JSON response: {e}"
        print(msg, file=sys.stderr)
        logger.exception(msg)
        sys.exit(1)

    if isinstance(parsed, dict) and parsed.get("error"):
        msg = f"API returned error: {parsed.get('error')}"
        print(msg, file=sys.stderr)
        logger.error(msg)
        sys.exit(1)

    try:
        save_json_to_file(parsed, from_c, to_c, date_str)
    except Exception as e:
        msg = f"Failed to save data: {e}"
        print(msg, file=sys.stderr)
        logger.exception(msg)
        sys.exit(1)


if __name__ == "__main__":
    main()