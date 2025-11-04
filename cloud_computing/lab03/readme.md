# Лабораторная работа №3. Облачные сети
  
## Цель
Научиться вручную создавать виртуальную сеть (VPC) в AWS, добавлять в неё подсети, таблицы маршрутов, интернет-шлюз (IGW) и NAT Gateway, а также настраивать взаимодействие между веб-сервером в публичной подсети и сервером базы данных в приватной.
  
## Ход работы
  
### Шаг 1. Подготовка среды
  
Вхожу в AWS Management Console. Убеждаюсь, что регион установлен на Frankfurt (eu-central-1). В строке поиска ввожу VPC и открываю консоль. Взяла салфеточки, чтобы слезы вытирать, и погнали.
  
---
  
### Шаг 2. Создание VPC
  
В левой панели выбираю `Your VPCs` → `Create VPC`. Указываю `Name tag` - "student-vpc-k15" (где 15 — мой порядковый номер). `IPv4 CIDR block` - "10.15.0.0/16":
  
<img width="1891" height="764" alt="image" src="https://github.com/user-attachments/assets/cf5bdbd8-e32c-42f7-af2b-c723e01b5e26" />

  
  
 > **Что обозначает маска /16? И почему нельзя использовать, например, /8?** Маска /16 означает, что первые 16 бит адреса фиксированы, а остальные 16 бит можно использовать для хостов, всего ~65 000 адресов. Использовать /8 нельзя, потому что это слишком большая сеть (~16 млн адресов), AWS такие большие VPC не поддерживает и это неуправляемо.
  
  
VPC успешно создана:
  
<img width="1625" height="423" alt="image" src="https://github.com/user-attachments/assets/98c4c28f-6eca-405c-a6df-c5e68ac2f2ce" />

  
---
  
### Шаг 3. Создание Internet Gateway (IGW)
  
В левой панели выбираю `Internet Gateways` → `Create internet gateway`. Указываю имя: "student-igw-k15".
  
<img width="1623" height="525" alt="image" src="https://github.com/user-attachments/assets/41aab8fd-7e68-4bae-bc75-64cc29cada5a" />
 
  
Теперь соединяю шлюз к созданной сети. Выбираю IGW, нажимаю `Actions` → `Attach to VPC`:
  
<img width="1649" height="254" alt="image" src="https://github.com/user-attachments/assets/b5f27665-c513-4fca-95b4-100bea41a74b" />

  
В списке выбираю student-vpc-k15 и подтверждаю:
  
<img width="1606" height="337" alt="image" src="https://github.com/user-attachments/assets/a5dfa459-19ec-4917-b738-3d22fe8f8fc1" />

  
---
  
### Шаг 4. Создание подсетей
  
Подсети (subnets) — это сегменты внутри VPC, которые позволяют изолировать ресурсы. То есть, подсети создаются для разделения ресурсов по функционалу и уровню доступа и для более гибкого управления трафиком. 
(ﾉಥ益ಥ)ﾉ
  
#### Шаг 4.1. Создание публичной подсети
  
В левой панели выбираю `Subnets` → `Create subnet`. Указываю `VPC ID` - сеть "student-vpc-k15":
  
<img width="1850" height="335" alt="image" src="https://github.com/user-attachments/assets/932959de-d496-405f-9edd-ef20830b993d" />

  
Далее указываю `Subnet name` - "public-subnet-k15", `Availability Zone` - "us-central-1a", `IPv4 CIDR block` - "10.15.1.0/24":
  
<img width="1847" height="593" alt="image" src="https://github.com/user-attachments/assets/7e7dd45b-4d16-489a-9ff1-364e6f91f133" />
 
  
 > **Является ли подсеть "публичной" на данный момент? Почему?** На данный момент подсеть ещё не является публичной, на просто создана внутри VPC, но не имеет маршрута в Internet Gateway (IGW). Чтобы подсеть стала публичной, нужно добавить в её маршрутную таблицу
  
#### Шаг 4.2. Создание приватной подсети
  
Нажимаю `Create subnet` ещё раз. В `VPC ID` - выбираю ту сеть "student-vpc-k15":
  
<img width="1866" height="340" alt="image" src="https://github.com/user-attachments/assets/f447372b-c4e0-489d-80ab-451a76b18994" />

  
`Subnet name` - "private-subnet-k15", `Availability Zone` - выбираю "us-central-1b", `IPv4 CIDR block` - "10.15.2.0/24":
  
<img width="1841" height="596" alt="image" src="https://github.com/user-attachments/assets/3a46a294-0ce5-467a-ab13-7066ec3ad9ab" />

  
  
> **Является ли подсеть "приватной" на данный момент? Почему?** Эта подсеть является приватной, потому что у неё нет маршрута в Internet Gateway (IGW). Трафик из неё не может напрямую попасть в Интернет, а доступ возможен только внутри VPC.
  
---
  
### Шаг 5. Создание таблиц маршрутов (Route Tables)
  
Теперь, когда у нас есть две подсети (публичная и приватная), необходимо настроить маршруты (Route Tables), которые определяют, как сетевой трафик будет двигаться внутри нашей VPC. (ﾉಥ益ಥ)ﾉ
  
#### Шаг 5.1. Создание публичной таблицы маршрутов
  
В левой панели выбираю `Route Tables` → `Create route table`. Указываю `Name tag` - "public-rt-k15", `VPC` - "student-vpc-k15". Подтверждаю создание таблицы:
  
<img width="1879" height="316" alt="image" src="https://github.com/user-attachments/assets/83f02742-63c6-4c89-bd32-29d6fce82929" />

  
Перехожу на вкладку `Routes` и нажмимаю `Edit routes` → `Add route`. Заполняю `Destination` - "0.0.0.0/0", `Target` - выбираю Internet Gateway "student-igw-k15":
  
<img width="1845" height="391" alt="image" src="https://github.com/user-attachments/assets/94ef1aaf-e309-412f-8deb-64b25b8fdda3" />

  
Перехожу на вкладку `Subnet associations` → `Edit subnet associations`. Отмечаю "public-subnet-k15" и сохраняю привязку.
  
<img width="1870" height="477" alt="image" src="https://github.com/user-attachments/assets/2d3f370d-d74c-4105-8d62-d0f5f5915f4e" />

  
> **Зачем необходимо привязывать таблицу маршрутов к подсети?** Привязка нужна, чтобы определить, по каким правилам будет идти трафик из этой конкретной подсети. Если таблица не привязана, подсеть использует основную (main) таблицу — в ней нет маршрута к Интернету, поэтому трафик просто не выйдет наружу.
  
  
#### Шаг 5.2. Создание приватной таблицы маршрутов
  
Нажимаю `Create route table` ещё раз. Указываю `Name tag` - "private-rt-k15" и `VPC` - student-vpc-k15. Сохраняю таблицу:
  
<img width="1862" height="603" alt="image" src="https://github.com/user-attachments/assets/0ade3c4f-e3f6-47c2-aa0d-b6c763e4d73c" />

  
Перехожу на вкладку `Subnet associations` → `Edit subnet associations`. Отмечаю "private-subnet-k15" и подтверждаю объединение:
  
<img width="1861" height="476" alt="image" src="https://github.com/user-attachments/assets/21f54655-330a-4a22-b19d-7b8aa9ac2825" />

  
  
---
  
### Шаг 6. Создание NAT Gateway
  
NAT Gateway позволяет ресурсам в приватной подсети выходить в Интернет (например, для обновления ПО), при этом оставаясь недоступными извне. (ﾉಥ益ಥ)ﾉ
  
> **Как работает NAT Gateway?** NAT Gateway принимает исходящий трафик от ресурсов в приватной подсети, меняет их внутренние IP-адреса на свой публичный, отправляет запросы в Интернет и возвращает ответы обратно. Таким образом, приватные инстансы могут обращаться наружу, но внешние серверы не могут инициировать соединение обратно — сохраняется изоляция.
  
#### Шаг 6.1. Создание Elastic IP
  
В левой панели выбираю `Elastic IPs` → `Allocate Elastic IP address` и `Allocate`:
  
<img width="1652" height="663" alt="image" src="https://github.com/user-attachments/assets/d12fb83c-ad77-4d1e-9cf5-45e58c21753f" />

  
#### Шаг 6.2. Создание NAT Gateway
  
В левой панели выбираю `NAT Gateways` → `Create NAT gateway`. Указываю `Name tag` - "nat-gateway-k15", `Subnet` - выбираю публичную подсеть "public-subnet-k15", `Connectivity type` - "Public", `Elastic IP allocation ID` - выбираю EIP, созданный на предыдущем шаге.
  
<img width="1846" height="596" alt="image" src="https://github.com/user-attachments/assets/47487b09-98a4-4227-b816-8028c159bc58" />

  
### Шаг 6.3. Изменение приватной таблицы маршрутов
  
Возвращаюсь в `Route Tables` и выбираю "private-rt-k15". Перехожу на вкладку `Routes` и нажимаю `Edit routes` → `Add route`.
  
<img width="1617" height="571" alt="image" src="https://github.com/user-attachments/assets/c227d632-d8f0-462a-b711-f3fb30107cc8" />

  
Заполняю `Destination` - "0.0.0.0/0", `Target` выбираю "nat-gateway-k15" и сохраняю изменения:
  
<img width="1841" height="385" alt="image" src="https://github.com/user-attachments/assets/fd39e99f-aeae-402c-b723-3631e466e661" />


  
---
  
### Шаг 7. Создание Security Groups

В левой панели выбираю `Security Groups` → `Create security group`. Указываю `Security group name` - "web-sg-k15", `Description` - "Security group for web server", `VPC` - выбираю свою VPC "student-vpc-k15":
  
<img width="1856" height="384" alt="image" src="https://github.com/user-attachments/assets/796fc8d3-4621-429a-92fa-af7d424872df" />

  
В разделе `Inbound rules` добавляю правила разрешающее следующие типы трафика:
  
<img width="1843" height="320" alt="image" src="https://github.com/user-attachments/assets/51cd0d01-75b9-46c0-bf80-dd9f28928e47" />



  
Далее создаю еще одну `Security Group`. `Security group name` - "bastion-sg-k15" с соответствуюзем описанием для bastion host:
  
<img width="1862" height="385" alt="image" src="https://github.com/user-attachments/assets/a36ff8ab-2ef2-4b12-afb6-29fd3d49d6f7" />



Разрешаю входящий трафика на порт 22 (SSH) только со своего IP-адреса:
  
<img width="1841" height="241" alt="image" src="https://github.com/user-attachments/assets/0cd444ce-c850-4042-9bc0-9969309a1e1c" />



  
И создаю третий `Security Group`: `Security group name` - "b-sg-k15" для базы данных:
  
<img width="1851" height="383" alt="image" src="https://github.com/user-attachments/assets/8324b9b4-08fc-4edb-b29b-1f3fe9cbcf4b" />

  
Со следующими входящими трафиками:
  
<img width="1839" height="401" alt="image" src="https://github.com/user-attachments/assets/01cd0505-fc4e-4b7e-a828-22ee447452a5" />

  
  
> **Что такое Bastion Host и зачем он нужен в архитектуре с приватными подсетями?** Bastion Host — это публичный сервер, через который администраторы получают доступ к ресурсам в приватной подсети. Он нужен, чтобы безопасно управлять приватными инстансами, не открывая прямой доступ из Интернета.
  
---
  
### Шаг 8. Создание EC2-инстансов
  
Создаю три EC2-инстанса, которые будут выполнять следующие роли:
* Веб-сервер (web-server) - в публичной подсети, доступен из Интернета по HTTP.
* Сервер базы данных (db-server) - в приватной подсети, недоступен напрямую извне.
* Bastion Host (bastion-host) - в публичной подсети, для безопасного доступа к приватным ресурсам.
  
  
Для `web-server` выбираю следующеие параметры:
  
<img width="1237" height="235" alt="image" src="https://github.com/user-attachments/assets/e6cd4d25-a4f5-432d-912a-e809fbf3c836" />

<img width="1225" height="681" alt="image" src="https://github.com/user-attachments/assets/003c4496-3b93-4dea-a69c-8af894049852" />

<img width="1222" height="480" alt="image" src="https://github.com/user-attachments/assets/944d3450-8cb2-4239-a0a0-a256e1c826bb" />

  
Для `db-server`:
  
<img width="1254" height="236" alt="image" src="https://github.com/user-attachments/assets/9d83325d-6615-40c9-91e0-d9e3fdbcaac9" />

<img width="1227" height="682" alt="image" src="https://github.com/user-attachments/assets/c2c78fc2-057e-409e-9d76-9d070aa139ee" />

<img width="1226" height="463" alt="image" src="https://github.com/user-attachments/assets/b15b54e0-038c-42b6-bbbf-3c58b8bd5644" />

  
Для `bastion-host`:
  
<img width="1229" height="162" alt="image" src="https://github.com/user-attachments/assets/43908c4c-41e5-48d3-ab5f-9b47d0a144ea" />

<img width="1223" height="683" alt="image" src="https://github.com/user-attachments/assets/185ed684-19df-4676-b7b3-27efb4adab9e" />

<img width="1225" height="474" alt="image" src="https://github.com/user-attachments/assets/736c5a0c-3efa-46e1-8391-ab95a1a91abe" />

  
Для всех трех инстансов использую: `AMI` - "Amazon Linux 2 AMI (HVM)", `Тип инстанса` - "t3.micro", `Ключ доступа (Key Pair)` - создаю "student-key-k15" и скачиваю его, `Хранилище` - оставляю по умолчанию 8 ГБ, `Tags` - добавляю тег `Name` с соответствующим именем инстанса:
  
<img width="1230" height="664" alt="image" src="https://github.com/user-attachments/assets/780f3f59-30ab-4b1b-a338-962734726bc0" />

<img width="1219" height="231" alt="image" src="https://github.com/user-attachments/assets/71cefce5-2b84-431d-b67a-031305e03ea6" />

<img width="1228" height="337" alt="image" src="https://github.com/user-attachments/assets/92a21f88-2476-4409-bb26-c28f143830d0" />

<img width="1226" height="210" alt="image" src="https://github.com/user-attachments/assets/83b9d844-67e8-40f8-8d2a-173ad89a815c" />


  
---
  
### Шаг 9. Проверка работы
  
Жду пока все инстансы, созданные в прошлом шаге, запустятся:
  
<img width="1893" height="236" alt="image" src="https://github.com/user-attachments/assets/d6fd194f-00a5-4f6b-b5e3-699c33b50dec" />

  
Далее, используя публичный адрес `web-server`, октрываю его в браузере. Появляется страница с информацией о PHP:
  
<img width="1915" height="220" alt="image" src="https://github.com/user-attachments/assets/8cca3cfc-0dc1-4aab-8f18-8487b409b6a4" />

  
Подключаюсь к `bastion-host` по SSH и после проверяю подключение к интернету, выполнив ping:
```
ssh -i student-key-k15.pem ec2-user@35.158.123.115
```
  
<img width="1444" height="446" alt="image" src="https://github.com/user-attachments/assets/dedfd7f4-8508-43eb-8a43-cfbf558be250" />

  
С `bastion-host` пробую подключиться к `db-server` *(не пробуется)* :
```
mysql -h 10.15.2.143 -u root -p
```
  
<img width="960" height="215" alt="image" src="https://github.com/user-attachments/assets/4db4dce9-9d14-47f7-b688-0504e98beb7e" />

 
---
  
### Шаг 10. Дополнительные задания. Подключение в приватную подсеть через Bastion Host
  
На своей локальной машине запускаю SSH Agent и проверяю подключение:
```
Start-Service ssh-agent
Get-Service ssh-agent
```
  
<img width="1470" height="221" alt="image" src="https://github.com/user-attachments/assets/cdf9d3a0-49ae-4529-be5d-bd521c0da3ff" />

  
Добавляю ключ в агент и подключаюсь к `bastion-host`:
```
ssh-add student-key-k15.pem
ssh -A ec2-user@63.176.145.215
```
  
<img width="1469" height="471" alt="image" src="https://github.com/user-attachments/assets/d3325e29-32bb-4ac1-b29b-296173d93cde" />

  
Из `bastion-host` перехожу к `db-server`:
```
ssh ec2-user@10.15.2.220
```
<img width="1468" height="520" alt="image" src="https://github.com/user-attachments/assets/3fb79db7-f7b6-40cb-be37-58424ee33031" />

  
Обновляю систему на `db-server`:
```
sudo dnf update -y
```
  
<img width="1469" height="518" alt="image" src="https://github.com/user-attachments/assets/6b96c827-f8d7-47e0-85b9-32f3102fd60a" />

  
Устанавливаю htop:
```
sudo dnf install -y htop
```
  
<img width="1467" height="746" alt="image" src="https://github.com/user-attachments/assets/3f53712b-ece3-4a6f-8ae2-2b2bb939806f" />

  
Подключаюсь к MySQL серверу:
```
mysql -u root -p
```
  
<img width="1472" height="334" alt="image" src="https://github.com/user-attachments/assets/9b296e6b-2768-4224-bbd7-706c6be507ea" />

  
Выхожу из MYSQL и затем из `db-server` и `bastion-host`. На локальном компьютере завершаю работу с SSH Agent:
```
ssh-agent -k
```
  
<img width="1480" height="104" alt="image" src="https://github.com/user-attachments/assets/d81cfc3c-accb-45cd-84b4-d2d9f6f73787" />

  
---
  
### Завершение работы

В последнем шаге выполняю удаление в строгом порядке:
- Удаляю все три инстанса: `web-server`, `bastion-host` и `db-server`:
  
<img width="1900" height="272" alt="image" src="https://github.com/user-attachments/assets/1b7816cd-62fc-464d-871a-981f13f2ced4" />
  
- Удаляю `NAT Gateway`:
  
<img width="1900" height="227" alt="image" src="https://github.com/user-attachments/assets/bc71eaa0-21a8-4ed8-b206-ff77ee97b0f4" />


  
- Удаляю `Security Groups`:
  
<img width="1896" height="158" alt="image" src="https://github.com/user-attachments/assets/8026aa56-de21-49ae-8a13-0201a6307e56" />

  
- Удаляю `Internet Gateway`:
  
<img width="1892" height="120" alt="image" src="https://github.com/user-attachments/assets/7af19b73-1546-4d37-af9d-344c151ac81c" />

  
- Удаляю созданную `VPC`:
  
<img width="1894" height="178" alt="image" src="https://github.com/user-attachments/assets/46432f45-9ae4-4f87-abd1-74c938b39684" />

  
## Заключение
  
В ходе лабораторной работы была создана и настроена облачная сеть (VPC) в AWS, включающая публичную и приватную подсети, интернет-шлюз и NAT Gateway. Настроены таблицы маршрутов и группы безопасности, обеспечивающие безопасное взаимодействие между компонентами. Были развернуты EC2-инстансы: веб-сервер, база данных и bastion host, и проверена их корректная работа. В результате освоены практические навыки построения защищённой сетевой архитектуры и подключения к приватным ресурсам через Bastion Host.
  
## Библиография
  
1. https://github.com/MSU-Courses/cloud-computing/tree/main/_lab/03_Cloud_Networking
2. https://eu-central-1.console.aws.amazon.com/console/home?region=eu-central-1#
3. https://docs.aws.amazon.com/vpc/latest/userguide/what-is-amazon-vpc.html
4. https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/concepts.html
