FROM jenkins/ssh-agent

# Установка PHP CLI (чтобы агент мог запускать PHP-тесты)
RUN apt-get update && apt-get install -y php-cli && rm -rf /var/lib/apt/lists/*
