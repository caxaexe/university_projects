FROM ubuntu:22.04

RUN apt-get update && \
    apt-get install -y python3 python3-pip cron && \
    pip3 install requests

WORKDIR /app

COPY currency_exchange_rate.py /app/
COPY cronjob /etc/cron.d/lab03_cron
COPY entrypoint.sh /app/

RUN chmod +x /app/entrypoint.sh
RUN chmod 644 /etc/cron.d/lab03_cron


ENTRYPOINT ["/app/entrypoint.sh"]