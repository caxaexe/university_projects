# Лабораторная работа №2. Введение в AWS. Вычислительные сервисы

## Цель
Познакомиться с основными вычислительными сервисами AWS, научиться создавать и настраивать виртуальные машины (EC2), а также развёртывать простые веб-приложения.

## Ход работы

### Задание 1. Создание IAM группы и пользовател

Открыв сервис `IAM`, создаю группу под названием "Admins":  
  
<img width="1812" height="731" alt="image" src="https://github.com/user-attachments/assets/2b62812a-9a84-44e8-84bc-aecaeabd5408" />  
<img width="1871" height="250" alt="image" src="https://github.com/user-attachments/assets/c5e23d91-1924-4a16-9414-7babcc0be9b9" />  

  
> **Что делает политика AdministratorAccess?** Политика AdministratorAccess предоставляет полный административный доступ ко всем ресурсам AWS. Это эквивалент суперпользователя (root), но без возможности изменять данные самого root-аккаунта.  
  
Далее создаю нового пользователя, дав ему имя и права к `AWS Management Console`:    
  
<img width="1328" height="730" alt="image" src="https://github.com/user-attachments/assets/8478f713-c099-4be6-a5dc-27fbea83d3a4" />  
  
Добавляю этого юзера к ранее созданной группе `Admins`:  
  
<img width="1866" height="654" alt="image" src="https://github.com/user-attachments/assets/40aa3757-43d0-40e0-ad65-2f0517d9e064" />  
  
Убеждаюсь, что новый юзер точно создан и имеет доступ к AWS консоли. Выхожу из root-аккаунта и захожу под новым IAM пользователем.

---

### Задание 2. Настройка Zero-Spend Budget

Открыв сервис `Billing and Cost Management`, настраиваю `Zero-Spend Budget`, введя соответствующее название и мой емайл, куда будут высылать уведомления, если расходы превысят 0 дорралов:  
  
<img width="1859" height="248" alt="image" src="https://github.com/user-attachments/assets/5a316620-5dd1-4da4-9c72-979cd8e89ceb" />
  
---
  
### Задание 3. Создание и запуск EC2 экземпляра (виртуальной машины)
  
Открываю сервис `EC2` и выбираю `Launch instances` чтобы создать и запустить виртуальную машину. При настройке следую инструкции из условия:  
  
1. Name and tags: webserver.
<img width="1251" height="239" alt="image" src="https://github.com/user-attachments/assets/d9893624-ebc9-4284-981a-dfcc206e7808" />
  
2. AMI: Amazon Linux 2023 AMI.  
<img width="1246" height="544" alt="image" src="https://github.com/user-attachments/assets/7007c254-d3b7-41cf-87b3-e072ab41ba89" />
  
3. Instance type: t3.micro.  
<img width="1230" height="245" alt="image" src="https://github.com/user-attachments/assets/1e9d20a4-8974-4b53-bd0f-c9ba11385285" />
  
4. Key pair: caxa-key.pem.  
<img width="1237" height="216" alt="image" src="https://github.com/user-attachments/assets/d59157e9-4a38-45b4-80ac-c3850e71e035" />
  
5. Network settings: по умолчанию. Security group: webserver-sg.
<img width="1230" height="643" alt="image" src="https://github.com/user-attachments/assets/afbed0b6-5df1-4856-8edd-a19118c12ca4" />
  
6. Разрешить HTTTP трафик с любого айпи адреса и SSH с моего текущего айпи адреса для Security group.  
<img width="1229" height="681" alt="image" src="https://github.com/user-attachments/assets/bcb58f71-1119-4932-b228-dc92938fba77" />
  
7. Configure Storage: по умолчанию.  
<img width="1235" height="355" alt="image" src="https://github.com/user-attachments/assets/43185de9-3bf5-462f-a3c7-cd6eccfdc528" />  
  
8. Advanced details → User Data: вставить скрипт.
<img width="1247" height="550" alt="image" src="https://github.com/user-attachments/assets/18fdd4f0-736e-4684-9e0e-527e68df84a9" />  

  > **Что такое User Data и какую роль выполняет данный скрипт? Для чего используется nginx?** *User Data* - это скрипт, который выполняется автоматически при первом запуске EC2-инстанса. Данный скрипт обновляет систему, устанавливает утилиту htop и веб-сервер nginx, затем включает и запускает nginx. *Nginx* - это лёгкий и быстрый веб-сервер, используемый для развёртывания простого веб-сервера, который будет доступен по публичному IP-адресу.  

После запуска экземпляра, дожидаюсь статуса `Running` и `Status checks: 3/3`, после этого появляется публичный ее айпи адрес в колонке "IPv4 Public IP".  
<img width="1836" height="686" alt="image" src="https://github.com/user-attachments/assets/65c9aea3-2e6b-4f85-99e8-a0258e5cd82e" />  
  
Проверяю, что веб-сервер работает, открыв в браузере URL: https://63.179.87.90:  
<img width="1913" height="288" alt="image" src="https://github.com/user-attachments/assets/0b1c260b-fd07-4d85-9691-af8041672392" />
  
---
  
### Задание 4. Логирование и мониторинг
 
Находясь в карточке моей виртуальной машины, открываю вкладку `Status checks`, все проверки прошли успешно.  
  
<img width="1835" height="218" alt="image" src="https://github.com/user-attachments/assets/2e9deb63-ed44-464d-b1d1-5f8a2f26bdea" />  
  
Проверяю вкладку `Monitoring`. 
  
<img width="1842" height="387" alt="image" src="https://github.com/user-attachments/assets/f10826d7-709e-4e6a-af98-9725cf44790a" />  
  
  
> **В каких случаях важно включать детализированный мониторинг?** Детализированный мониторинг стоит включать, когда требуется оперативная реакция на изменения нагрузки, при настройке автоматического масштабирование, когда необходимо точное SLA-отслеживание или детальные отчёты производительности.
  
  
Проверяю системные логи `System Log`, в качестве примера ищу строки с установкой `nginx`.
  
<img width="1833" height="630" alt="image" src="https://github.com/user-attachments/assets/09b0f956-cc70-4f30-a25b-44b24f963953" />  

Просматриваю снимок экрана инстанса `Instance Screenshot`, в меню выбираю `Actions` → `Monitor and troubleshoot` → `Get instance screenshot`.  
  
<img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/00e48320-c5e8-45f3-8a46-ead288183e3e" />
  
---
  
### Задание 5. Подключение к EC2 инстансу по SSH
  
Сохранив на этапе настройки виртуальной машины ключ в безопасном месте, я настраиваю права доступа, разрешив всем, кроме админа(меня скромную), только чтение файла.  
  
<img width="534" height="646" alt="image" src="https://github.com/user-attachments/assets/7aa8b122-18fc-44f1-b05f-f4afaea4bf9c" />
  
В комадной строке, подключаюсь к инстансу по SSH:
```ssh -i caxa-key.pem ec2-user@63.179.87.90``` где, "-i - параметр, указывающий на файл приватного ключа, "caxa-key.pem" - имя файла с приватным ключом, "ec2-user" - стандартное имя пользователя для Amazon Linux AMI, "63.179.87.90" - публичный IP-адрес инстанса EC2.   
  
<img width="1096" height="308" alt="image" src="https://github.com/user-attachments/assets/048b5f8d-56fa-49dd-82de-5588269f9c50" />  
  
Проверяю статус веб-сервера `nginx`, используя команду:
```systemctl status nginx```.  
  
<img width="1093" height="639" alt="image" src="https://github.com/user-attachments/assets/acc2b409-3f0d-4385-b841-5167a29b160a" />
  
  
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
  
<img width="1100" height="145" alt="image" src="https://github.com/user-attachments/assets/499a4707-1d98-419b-9a7e-0e9f225b6af4" />  
  
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
  
<img width="1560" height="271" alt="image" src="https://github.com/user-attachments/assets/7fe9396d-e50c-46c7-b727-358fc0c1619f" />  
  
Обновляю содержимое файла `docker-compose.yml`:  
  
<img width="1890" height="850" alt="image" src="https://github.com/user-attachments/assets/c1af70ee-88e4-465e-b3f9-5a1a75041c79" />
  
А затем `default.conf`:
  
<img width="1900" height="326" alt="image" src="https://github.com/user-attachments/assets/0af089e5-8539-46d3-986a-875880253ee4" />  
  
Чтобы проект запустился с пересборкой всех образов и работал в фоне, использую команду:
```
docker-compose up -d --build
```
  
<img width="1901" height="152" alt="image" src="https://github.com/user-attachments/assets/4fe6e10c-c00f-4d50-8c3c-d3a40e72890c" />
  
Затем проверяю все запущенные контейнеры:
```
docker ps
```
<img width="1900" height="144" alt="image" src="https://github.com/user-attachments/assets/8fb4d548-19c1-4c3b-b35e-114e5ee55479" />  
  
`nginx` — веб-сервер, принимающий HTTP-запросы и перенаправляющий их в PHP-обработчик.
`php-fpm` — сервис для интерпретации и выполнения PHP-кода.
`mysql` — реляционная база данных для хранения информации приложения.
`adminer` — лёгкий веб-интерфейс для администрирования базы данных.
  
  
После запуска проверяю доступно ли приложение по публичному адресу `http://3.68.227.15`(айпи другой пушто пересобирала все). Как можно заметить на скрине все великолепно РАБОТАЕТ ЮХУУУУУ:  
  
<img width="1913" height="966" alt="image" src="https://github.com/user-attachments/assets/7bab7cf4-2319-479b-b744-fceae553d500" />
  
---
  
### Задание 7. Завершение работы и удаление ресурсов

Останавливаю виртуальную машину, используя команду:
```
aws ec2 stop-instances --instance-ids i-0b6cb446b661470e4
```
<img width="1087" height="368" alt="image" src="https://github.com/user-attachments/assets/04b43d14-7414-406e-ba33-d61fb6fbb178" />
  
Проверяю на Amazon AWS, виртуальная машина была успешно остановлена.  
  
<img width="1881" height="209" alt="image" src="https://github.com/user-attachments/assets/8e2d7701-2d6e-4371-a56c-0dcfdaf538d5" />
  
> **Чем «Stop» отличается от «Terminate»?** `Stop` останавливает виртуальную машину, но сохраняет все данные на диске, а `Terminate` полностью удаляет виртуальную машину вместе с её диском и данными.

## Заключение
В ходе работы я познакомилась с основами использования AWS EC2 и научилась управлять облачными ресурсами. Настроила пользователя IAM, создала виртуальную машину с Amazon Linux и Nginx, подключилась по SSH и изучила мониторинг через CloudWatch. Также попробовала развернуть PHP-приложение в Docker-контейнерах и разобралась в различиях между остановкой и удалением инстанса, чтобы правильно завершать работу без лишних затрат.

## Библиография
1. https://elearning.usm.md/mod/assign/view.php?id=315493
2. https://eu-central-1.console.aws.amazon.com/console/home?region=eu-central-1#
3. https://nginx.org/en/docs/










