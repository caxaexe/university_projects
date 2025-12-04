# Лабораторная работа №2. Введение в AWS. Вычислительные сервисы

## Цель
Познакомиться с основными вычислительными сервисами AWS, научиться создавать и настраивать виртуальные машины (EC2), а также развёртывать простые веб-приложения.

## Ход работы

### Задание 1. Создание IAM группы и пользовател

Открыв сервис `IAM`, создаю группу под названием "Admins":  
  
<img width="1812" height="731" alt="image" src="https://github.com/user-attachments/assets/20c6bb78-a13c-4334-99e1-d8eaba856cbc" />

 
<img width="1871" height="250" alt="image" src="https://github.com/user-attachments/assets/d44b31ab-a6fc-4cbe-a1ab-b6c458a6fc04" />

  

  
> **Что делает политика AdministratorAccess?** Политика AdministratorAccess предоставляет полный административный доступ ко всем ресурсам AWS. Это эквивалент суперпользователя (root), но без возможности изменять данные самого root-аккаунта.  
  
Далее создаю нового пользователя, дав ему имя и права к `AWS Management Console`:    
  
<img width="1328" height="730" alt="image" src="https://github.com/user-attachments/assets/2af49e9c-afa1-4c85-a53b-57535653bdea" />
  
  
Добавляю этого юзера к ранее созданной группе `Admins`:  
  
<img width="1866" height="654" alt="image" src="https://github.com/user-attachments/assets/eacf0847-2f46-4f47-b776-9b3948ebdf75" />
 
  
Убеждаюсь, что новый юзер точно создан и имеет доступ к AWS консоли. Выхожу из root-аккаунта и захожу под новым IAM пользователем.

---

### Задание 2. Настройка Zero-Spend Budget

Открыв сервис `Billing and Cost Management`, настраиваю `Zero-Spend Budget`, введя соответствующее название и мой емайл, куда будут высылать уведомления, если расходы превысят 0 дорралов:  
  
<img width="1859" height="248" alt="image" src="https://github.com/user-attachments/assets/01453ada-3a94-4a6e-9bc7-4b8115e8ce90" />

  
---
  
### Задание 3. Создание и запуск EC2 экземпляра (виртуальной машины)
  
Открываю сервис `EC2` и выбираю `Launch instances` чтобы создать и запустить виртуальную машину. При настройке следую инструкции из условия:  
  
1. Name and tags: webserver.
<img width="1251" height="239" alt="image" src="https://github.com/user-attachments/assets/415aa09f-5c90-4199-943b-54738f324bf3" />

  
2. AMI: Amazon Linux 2023 AMI.  
<img width="1246" height="544" alt="image" src="https://github.com/user-attachments/assets/c9f1a9f0-fd54-4e65-bf50-1828f0793565" />

  
3. Instance type: t3.micro.  
<img width="1230" height="245" alt="image" src="https://github.com/user-attachments/assets/fd63cd1d-bb38-4d66-a3cc-0376fb9b76e5" />

  
4. Key pair: caxa-key.pem.  
<img width="1237" height="216" alt="image" src="https://github.com/user-attachments/assets/80ced854-0f41-47b0-a55a-8f9859a4bebd" />

  
5. Network settings: по умолчанию. Security group: webserver-sg.
<img width="1230" height="643" alt="image" src="https://github.com/user-attachments/assets/99fcaa90-77ca-4901-8c9e-460eae2dbe28" />

  
6. Разрешить HTTTP трафик с любого айпи адреса и SSH с моего текущего айпи адреса для Security group.  
<img width="1229" height="681" alt="image" src="https://github.com/user-attachments/assets/30d08efa-7e72-4189-9cff-05498f255b2c" />

  
7. Configure Storage: по умолчанию.  
<img width="1235" height="355" alt="image" src="https://github.com/user-attachments/assets/c8b83c43-b5ce-4cce-92d4-0d693d1766d1" />

  
8. Advanced details → User Data: вставить скрипт.
<img width="1247" height="550" alt="image" src="https://github.com/user-attachments/assets/254365e5-a259-4cb3-bc04-1f474ba1962c" />
  

  > **Что такое User Data и какую роль выполняет данный скрипт? Для чего используется nginx?** *User Data* - это скрипт, который выполняется автоматически при первом запуске EC2-инстанса. Данный скрипт обновляет систему, устанавливает утилиту htop и веб-сервер nginx, затем включает и запускает nginx. *Nginx* - это лёгкий и быстрый веб-сервер, используемый для развёртывания простого веб-сервера, который будет доступен по публичному IP-адресу.  

После запуска экземпляра, дожидаюсь статуса `Running` и `Status checks: 3/3`, после этого появляется публичный ее айпи адрес в колонке "IPv4 Public IP".  
<img width="1836" height="686" alt="image" src="https://github.com/user-attachments/assets/426542f5-48f1-4375-b1b4-626da5a5702a" />
  
  
Проверяю, что веб-сервер работает, открыв в браузере URL: https://63.179.87.90:  
<img width="1913" height="288" alt="image" src="https://github.com/user-attachments/assets/bebc6c53-302b-4b33-840b-20eaf1912fb5" />

  
---
  
### Задание 4. Логирование и мониторинг
 
Находясь в карточке моей виртуальной машины, открываю вкладку `Status checks`, все проверки прошли успешно.  
  
<img width="1835" height="218" alt="image" src="https://github.com/user-attachments/assets/157602af-74ba-47e5-9681-4f5b3f252732" />

  
Проверяю вкладку `Monitoring`. 
  
<img width="1842" height="387" alt="image" src="https://github.com/user-attachments/assets/a98ed37c-a680-4910-97a1-3480e9c78761" />
  
  
  
> **В каких случаях важно включать детализированный мониторинг?** Детализированный мониторинг стоит включать, когда требуется оперативная реакция на изменения нагрузки, при настройке автоматического масштабирование, когда необходимо точное SLA-отслеживание или детальные отчёты производительности.
  
  
Проверяю системные логи `System Log`, в качестве примера ищу строки с установкой `nginx`.
  
<img width="1833" height="630" alt="image" src="https://github.com/user-attachments/assets/5c3e6387-e69a-4d85-baa7-1bd2de78373e" />
 

Просматриваю снимок экрана инстанса `Instance Screenshot`, в меню выбираю `Actions` → `Monitor and troubleshoot` → `Get instance screenshot`.  
  
<img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/b749cc2a-ba5e-4f54-8711-f35e8518e579" />

  
---
  
### Задание 5. Подключение к EC2 инстансу по SSH
  
Сохранив на этапе настройки виртуальной машины ключ в безопасном месте, я настраиваю права доступа, разрешив всем, кроме админа(меня скромную), только чтение файла.  
  
<img width="534" height="646" alt="image" src="https://github.com/user-attachments/assets/25b3a28c-3c7b-485c-a90a-d4220b5c6ea6" />

  
В комадной строке, подключаюсь к инстансу по SSH:
```ssh -i caxa-key.pem ec2-user@63.179.87.90``` где, "-i - параметр, указывающий на файл приватного ключа, "caxa-key.pem" - имя файла с приватным ключом, "ec2-user" - стандартное имя пользователя для Amazon Linux AMI, "63.179.87.90" - публичный IP-адрес инстанса EC2.   
  
<img width="1096" height="308" alt="image" src="https://github.com/user-attachments/assets/63d96506-ab1b-404e-88bf-be0f26b72b44" />
  
  
Проверяю статус веб-сервера `nginx`, используя команду:
```systemctl status nginx```.  
  
<img width="1093" height="639" alt="image" src="https://github.com/user-attachments/assets/26d412a5-dacd-4959-b8a7-294b4372ff87" />

  
  
> **Почему в AWS нельзя использовать пароль для входа по SSH?** В AWS нельзя использовать пароль для входа по SSH, потому что это небезопасно - пароли легко подобрать. Вместо них используют ключи, которые гораздо труднее взломать.
  
---
  
### Задание 6c. Запуск PHP-приложения в Docker  

Подключаюсь к инстансу EC2 по SSH точно так же как и до этого. Устанавливаю `Docker`:
```
sudo dnf -y install docker
sudo systemctl enable docker
sudo systemctl start docker
sudo usermod -aG docker ec2-user
```
И проверяю, что он работает:
```
docker --version
```
  
<img width="1100" height="145" alt="image" src="https://github.com/user-attachments/assets/2b9a96e3-828b-4664-8ab9-843b94738775" />

  
Перезахожу в сессию SHH, чтобы обновить группы пользователя.  
  
Прежде чем начать работу, создаю следующую структуру проекта:
```
php-docker-app/
├── app/
├── nginx/
│   └── default.conf
└── docker-compose.yml
```
  
Затем я копирую php-приложение на виртуальную машину:
```
scp -i "D:\University\Local\AWS\caxa-key.pem" -r "D:\University\Local\sawm\sawmlab3" ec2-user@63.179.87.90:/home/ec2-user/php-docker-app/app
```
  
<img width="1560" height="271" alt="image" src="https://github.com/user-attachments/assets/5d2c68b0-01cc-4dba-a7fc-4292b1ea5ed1" />
  
  
Обновляю содержимое файла `docker-compose.yml`:  
  
<img width="1890" height="850" alt="image" src="https://github.com/user-attachments/assets/1218a560-1f3f-4fab-bf47-333360d679e7" />

  
А затем `default.conf`:
  
<img width="1900" height="326" alt="image" src="https://github.com/user-attachments/assets/ea19f00c-6bfe-4d7c-a854-d1f5fd71ea8d" />
  
  
Чтобы проект запустился с пересборкой всех образов и работал в фоне, использую команду:
```
docker-compose up -d --build
```
  
<img width="1901" height="152" alt="image" src="https://github.com/user-attachments/assets/c8a444ed-04f0-4ce4-9825-56f7483fbfad" />

  
Затем проверяю все запущенные контейнеры:
```
docker ps
```
<img width="1900" height="144" alt="image" src="https://github.com/user-attachments/assets/3a563a9e-db31-4828-a79f-4197abc6bb8b" />
 
  
`nginx` — веб-сервер, принимающий HTTP-запросы и перенаправляющий их в PHP-обработчик.
`php-fpm` — сервис для интерпретации и выполнения PHP-кода.
`mysql` — реляционная база данных для хранения информации приложения.
`adminer` — лёгкий веб-интерфейс для администрирования базы данных.
  
  
После запуска проверяю доступно ли приложение по публичному адресу `http://3.68.227.15`(айпи другой пушто пересобирала все). Как можно заметить на скрине все великолепно РАБОТАЕТ ЮХУУУУУ:  
  
<img width="1913" height="966" alt="image" src="https://github.com/user-attachments/assets/1f48141e-48fa-479b-b0ef-090cb7db4d41" />

  
---
  
### Задание 7. Завершение работы и удаление ресурсов

Останавливаю виртуальную машину, используя команду:
```
aws ec2 stop-instances --instance-ids i-0b6cb446b661470e4
```
<img width="1087" height="368" alt="image" src="https://github.com/user-attachments/assets/36b229d3-c192-49bf-8b92-50d38bd00cd0" />

  
Проверяю на Amazon AWS, виртуальная машина была успешно остановлена.  
  
<img width="1881" height="209" alt="image" src="https://github.com/user-attachments/assets/3e7cd78a-6c2a-4f6c-9894-f57451807dcf" />

  
> **Чем «Stop» отличается от «Terminate»?** `Stop` останавливает виртуальную машину, но сохраняет все данные на диске, а `Terminate` полностью удаляет виртуальную машину вместе с её диском и данными.

## Заключение
В ходе работы я познакомилась с основами использования AWS EC2 и научилась управлять облачными ресурсами. Настроила пользователя IAM, создала виртуальную машину с Amazon Linux и Nginx, подключилась по SSH и изучила мониторинг через CloudWatch. Также попробовала развернуть PHP-приложение в Docker-контейнерах и разобралась в различиях между остановкой и удалением инстанса, чтобы правильно завершать работу без лишних затрат.

## Библиография
1. https://elearning.usm.md/mod/assign/view.php?id=315493
2. https://eu-central-1.console.aws.amazon.com/console/home?region=eu-central-1#
3. https://nginx.org/en/docs/










