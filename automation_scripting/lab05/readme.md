# Лабораторная работа №5. Ansible Playbook для настройки сервера

## Цель
Научиться создавать сценарии Ansible для автоматизации настройки сервера.

## Ход работы

### 1. Установка и настройка Jenkins
docker compose up -d
![alt text](images/image.png)

docker exec -it jenkins-controller cat /var/jenkins_home/secrets/initialAdminPassword
![alt text](images/image-1.png)


Жду установку всех необходимых плагинов:
![alt text](images/image-2.png)

![alt text](images/image-3.png)

Затем, перейдя во вкладку `Настройка Jenkins` -> `Plugins` устанавливаю дополнительные плагины для работы:
- Docker
- Docker Pipeline
- GitHub Integration
- SSH Agent
  
![alt text](images/image-4.png)

![alt text](images/image-11.png)


### 2. Настройка SSH-агента
```
ssh-keygen -t rsa -b 4096 -f jenkins_ssh_agent
```
![alt text](images/image-5.png)


```bash
chmod 600 jenkins_ssh_agent
chmod 644 jenkins_ssh_agent.pub
```


### 3. Создание Ansible Agent

```
ssh-keygen -t rsa -b 4096 -f jenkins_ansible_agent
```
![alt text](images/image-6.png)


```
ssh-keygen -t rsa -b 4096 -f ansible_to_testserver
```
![alt text](images/image-7.png)



### 4. Создание тестового сервера

### 5. Создание Ansible Playbook для настройки тестового сервера


```
docker compose down
docker compose up -d --build
```
![alt text](images/image-8.png)

```
docker ps
```
![alt text](images/image-9.png)


![alt text](images/image-10.png)

### 6. Конвейер для сборки и тестирования PHP-проекта

### 7. Конвейер для настройки тестового сервера с использованием Ansible

### 8. Конвейер для развертывания PHP-проекта на тестовом сервере

### 9. Тестирование развернутого PHP-проекта
![alt text](images/image-11.png)
![alt text](images/image-12.png)
![alt text](images/image-13.png)
![alt text](images/image-14.png)


`http://localhost:8081/`
![alt text](images/image-15.png)


## Контрольные вопросы
**1. Каковы преимущества использования Ansible для настройки сервера?**
Ansible обеспечивает простой и декларативный способ управления конфигурациями. Он не требует установки агентов на серверах, использует обычный SSH, а его плейбуки являются идемпотентными — повторный запуск не нарушает систему. Благодаря модульности и читаемости YAML-файлов Ansible легко поддерживать и масштабировать.

**2. Какие ещё модули Ansible существуют для управления конфигурацией?**
В Ansible существует множество модулей, среди которых apt/yum для пакетов, service для управления сервисами, user и group для управления пользователями, copy и template для файлов, git для работы с репозиториями, docker для контейнеров и systemd для служб. Эти модули позволяют автоматизировать практически любую задачу на сервере.

**3. С какими проблемами вы столкнулись при создании сценария Ansible и как вы их решили?**
Основные проблемы были связаны с правами доступа, отсутствующими пакетами и неверной конфигурацией SSH. Проблема с Apache устранялась запуском корректного playbook-а и настройкой виртуального хоста. Каждая проблема решалась поэтапной проверкой и корректировкой Dockerfile-ов, playbook-ов и hosts.ini.

## Вывод
В ходе выполнения лабораторной работы была развёрнута полноценная инфраструктура CI/CD на базе Docker, Jenkins и Ansible. Были настроены агенты для выполнения сборок и конфигурации серверов, создан тестовый сервер и написаны playbook-и для его автоматической настройки и деплоя PHP-приложения. В результате удалось полностью автоматизировать процесс установки окружения, тестирования и развертывания приложения, что демонстрирует преимущества современной DevOps-практики и подтверждает важность инструментов автоматизации в разработке и сопровождении систем.