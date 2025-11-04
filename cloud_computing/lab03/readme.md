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
  
<img width="1625" height="423" alt="image" src="https://github.com/user-attachments/assets/49a293a7-40f8-4603-bd41-50d65917f6e9" />
  
---
  
### Шаг 3. Создание Internet Gateway (IGW)
  
В левой панели выбираю `Internet Gateways` → `Create internet gateway`. Указываю имя: "student-igw-k15".
  
<img width="1623" height="525" alt="image" src="https://github.com/user-attachments/assets/958546fc-3f01-41f0-b087-2e6b5fcba15d" />  
  
Теперь соединяю шлюз к созданной сети. Выбираю IGW, нажимаю `Actions` → `Attach to VPC`:
  
<img width="1649" height="254" alt="image" src="https://github.com/user-attachments/assets/7013cd2d-52e4-4a21-a13b-2d11aa3ff000" />
  
В списке выбираю student-vpc-k15 и подтверждаю:
  
<img width="1606" height="337" alt="image" src="https://github.com/user-attachments/assets/194d4520-c676-4fe0-83b7-4cbbd9339f10" />
  
---
  
### Шаг 4. Создание подсетей
  
Подсети (subnets) — это сегменты внутри VPC, которые позволяют изолировать ресурсы. То есть, подсети создаются для разделения ресурсов по функционалу и уровню доступа и для более гибкого управления трафиком. 
(ﾉಥ益ಥ)ﾉ
  
#### Шаг 4.1. Создание публичной подсети
  
В левой панели выбираю `Subnets` → `Create subnet`. Указываю `VPC ID` - сеть "student-vpc-k15":
  
<img width="1850" height="335" alt="image" src="https://github.com/user-attachments/assets/506d3dd7-aeb7-4c09-8de0-5c1e37f015d7" />
  
Далее указываю `Subnet name` - "public-subnet-k15", `Availability Zone` - "us-central-1a", `IPv4 CIDR block` - "10.15.1.0/24":
  
<img width="1847" height="593" alt="image" src="https://github.com/user-attachments/assets/46b4f527-b057-43e2-8fef-412ecef5fc31" />  
  
 > **Является ли подсеть "публичной" на данный момент? Почему?** На данный момент подсеть ещё не является публичной, на просто создана внутри VPC, но не имеет маршрута в Internet Gateway (IGW). Чтобы подсеть стала публичной, нужно добавить в её маршрутную таблицу
  
#### Шаг 4.2. Создание приватной подсети
  
Нажимаю `Create subnet` ещё раз. В `VPC ID` - выбираю ту сеть "student-vpc-k15":
  
<img width="1866" height="340" alt="image" src="https://github.com/user-attachments/assets/e3077ade-a649-40f0-8d98-118c0b35f3b8" />
  
`Subnet name` - "private-subnet-k15", `Availability Zone` - выбираю "us-central-1b", `IPv4 CIDR block` - "10.15.2.0/24":
  
<img width="1841" height="596" alt="image" src="https://github.com/user-attachments/assets/208319dd-7f6e-441f-9cb4-1827495614b7" />
  
  
> **Является ли подсеть "приватной" на данный момент? Почему?** Эта подсеть является приватной, потому что у неё нет маршрута в Internet Gateway (IGW). Трафик из неё не может напрямую попасть в Интернет, а доступ возможен только внутри VPC.
  
---
  
### Шаг 5. Создание таблиц маршрутов (Route Tables)
  
Теперь, когда у нас есть две подсети (публичная и приватная), необходимо настроить маршруты (Route Tables), которые определяют, как сетевой трафик будет двигаться внутри нашей VPC. (ﾉಥ益ಥ)ﾉ
  
#### Шаг 5.1. Создание публичной таблицы маршрутов
  
В левой панели выбираю `Route Tables` → `Create route table`. Указываю `Name tag` - "public-rt-k15", `VPC` - "student-vpc-k15". Подтверждаю создание таблицы:
  
<img width="1879" height="316" alt="image" src="https://github.com/user-attachments/assets/5ddce595-1767-46b6-9da2-c1b8eea76b3a" />
  
Перехожу на вкладку `Routes` и нажмимаю `Edit routes` → `Add route`. Заполняю `Destination` - "0.0.0.0/0", `Target` - выбираю Internet Gateway "student-igw-k15":
  
<img width="1845" height="391" alt="image" src="https://github.com/user-attachments/assets/bc8fb9ad-b79e-49ce-a5d2-5392c554666e" />
  
Перехожу на вкладку `Subnet associations` → `Edit subnet associations`. Отмечаю "public-subnet-k15" и сохраняю привязку.
  
<img width="1870" height="477" alt="image" src="https://github.com/user-attachments/assets/33163702-8765-43fc-bd24-6cf321c7182e" />
  
> **Зачем необходимо привязывать таблицу маршрутов к подсети?** Привязка нужна, чтобы определить, по каким правилам будет идти трафик из этой конкретной подсети. Если таблица не привязана, подсеть использует основную (main) таблицу — в ней нет маршрута к Интернету, поэтому трафик просто не выйдет наружу.
  
  
#### Шаг 5.2. Создание приватной таблицы маршрутов
  
Нажимаю `Create route table` ещё раз. Указываю `Name tag` - "private-rt-k15" и `VPC` - student-vpc-k15. Сохраняю таблицу:
  
<img width="1862" height="603" alt="image" src="https://github.com/user-attachments/assets/b878b8c1-3b11-4662-90d0-706eac915cea" />
  
Перехожу на вкладку `Subnet associations` → `Edit subnet associations`. Отмечаю "private-subnet-k15" и подтверждаю объединение:
  
<img width="1861" height="476" alt="image" src="https://github.com/user-attachments/assets/e38a4c2e-0e97-42f6-aede-0684b9009e3a" />
  
  
---
  
### Шаг 6. Создание NAT Gateway
  
NAT Gateway позволяет ресурсам в приватной подсети выходить в Интернет (например, для обновления ПО), при этом оставаясь недоступными извне. (ﾉಥ益ಥ)ﾉ
  
> **Как работает NAT Gateway?** NAT Gateway принимает исходящий трафик от ресурсов в приватной подсети, меняет их внутренние IP-адреса на свой публичный, отправляет запросы в Интернет и возвращает ответы обратно. Таким образом, приватные инстансы могут обращаться наружу, но внешние серверы не могут инициировать соединение обратно — сохраняется изоляция.
  
#### Шаг 6.1. Создание Elastic IP
  
В левой панели выбираю `Elastic IPs` → `Allocate Elastic IP address` и `Allocate`:
  
<img width="1652" height="663" alt="image" src="https://github.com/user-attachments/assets/70219341-907a-4295-8a7e-14043be90bfb" />
  
#### Шаг 6.2. Создание NAT Gateway
  
В левой панели выбираю `NAT Gateways` → `Create NAT gateway`. Указываю `Name tag` - "nat-gateway-k15", `Subnet` - выбираю публичную подсеть "public-subnet-k15", `Connectivity type` - "Public", `Elastic IP allocation ID` - выбираю EIP, созданный на предыдущем шаге.
  
<img width="1846" height="596" alt="image" src="https://github.com/user-attachments/assets/72e94f3c-612c-43d4-ba82-71d42b829bae" />
  
### Шаг 6.3. Изменение приватной таблицы маршрутов
  
Возвращаюсь в `Route Tables` и выбираю "private-rt-k15". Перехожу на вкладку `Routes` и нажимаю `Edit routes` → `Add route`.
  
<img width="1617" height="571" alt="image" src="https://github.com/user-attachments/assets/5e9eada6-b2bd-4989-9165-9e67bcfde666" />
  
Заполняю `Destination` - "0.0.0.0/0", `Target` выбираю "nat-gateway-k15" и сохраняю изменения:
  
<img width="1841" height="385" alt="image" src="https://github.com/user-attachments/assets/90d23402-25ac-4787-8207-8f9606dfdd02" />

  
---
  
### Шаг 7. Создание Security Groups

В левой панели выбираю `Security Groups` → `Create security group`. Указываю `Security group name` - "web-sg-k15", `Description` - "Security group for web server", `VPC` - выбираю свою VPC "student-vpc-k15":
  
<img width="1856" height="384" alt="image" src="https://github.com/user-attachments/assets/f7d43ba1-915d-427f-96df-43093fea5862" />
  
В разделе `Inbound rules` добавляю правила разрешающее следующие типы трафика:
  
<img width="1843" height="320" alt="image" src="https://github.com/user-attachments/assets/2a1f45d6-9cce-4f65-8f73-aa80c7beb0cb" />


  
Далее создаю еще одну `Security Group`. `Security group name` - "bastion-sg-k15" с соответствуюзем описанием для bastion host:
  
<img width="1862" height="385" alt="image" src="https://github.com/user-attachments/assets/3e26e121-a6a5-433e-9d5b-a2402e0302a2" />

Разрешаю входящий трафика на порт 22 (SSH) только со своего IP-адреса:
  
<img width="1841" height="241" alt="image" src="https://github.com/user-attachments/assets/8a3f20ca-a05f-4fb6-a13a-200b3ef6ea36" />


  
И создаю третий `Security Group`: `Security group name` - "b-sg-k15" для базы данных:
  
<img width="1851" height="383" alt="image" src="https://github.com/user-attachments/assets/7eafadbe-0256-44ad-a380-01ea1bbf280c" />
  
Со следующими входящими трафиками:
  
<img width="1839" height="401" alt="image" src="https://github.com/user-attachments/assets/60a0f760-eb4e-4647-b777-368c367078fa" />
  
  
> **Что такое Bastion Host и зачем он нужен в архитектуре с приватными подсетями?** Bastion Host — это публичный сервер, через который администраторы получают доступ к ресурсам в приватной подсети. Он нужен, чтобы безопасно управлять приватными инстансами, не открывая прямой доступ из Интернета.
  
---
  
### Шаг 8. Создание EC2-инстансов
  
Создаю три EC2-инстанса, которые будут выполнять следующие роли:
* Веб-сервер (web-server) - в публичной подсети, доступен из Интернета по HTTP.
* Сервер базы данных (db-server) - в приватной подсети, недоступен напрямую извне.
* Bastion Host (bastion-host) - в публичной подсети, для безопасного доступа к приватным ресурсам.
  
  
Для `web-server` выбираю следующеие параметры:
  
<img width="1237" height="235" alt="image" src="https://github.com/user-attachments/assets/81e53f18-b9b5-4d8c-934a-2ab621622383" />
<img width="1225" height="681" alt="image" src="https://github.com/user-attachments/assets/264b04de-4d26-4f4e-a985-15270c3e15c2" />
<img width="1222" height="480" alt="image" src="https://github.com/user-attachments/assets/92430ebe-e2cd-45b1-b3a3-02eb3d8c0495" />
  
Для `db-server`:
  
<img width="1254" height="236" alt="image" src="https://github.com/user-attachments/assets/89fdf338-2d25-41a9-98f2-728d66741b92" />
<img width="1227" height="682" alt="image" src="https://github.com/user-attachments/assets/18480a48-309a-499f-90a2-e9726feb34d6" />
<img width="1226" height="463" alt="image" src="https://github.com/user-attachments/assets/3d2259c6-8d0c-4c18-8092-fd76cedb6432" />
  
Для `bastion-host`:
  
<img width="1229" height="162" alt="image" src="https://github.com/user-attachments/assets/8870a27d-b151-4e54-9668-c4b2d6a6b631" />
<img width="1223" height="683" alt="image" src="https://github.com/user-attachments/assets/5c496ada-4776-4045-8ee3-4d30cf4f7f48" />
<img width="1225" height="474" alt="image" src="https://github.com/user-attachments/assets/09e04a4a-c78c-43da-8b58-a3fc807e9d6b" />
  
Для всех трех инстансов использую: `AMI` - "Amazon Linux 2 AMI (HVM)", `Тип инстанса` - "t3.micro", `Ключ доступа (Key Pair)` - создаю "student-key-k15" и скачиваю его, `Хранилище` - оставляю по умолчанию 8 ГБ, `Tags` - добавляю тег `Name` с соответствующим именем инстанса:
  
<img width="1230" height="664" alt="image" src="https://github.com/user-attachments/assets/78544549-c320-4117-9a2d-5eeabfd23504" />
<img width="1219" height="231" alt="image" src="https://github.com/user-attachments/assets/971b567d-4994-4a55-812a-b0c3ab8bd29f" />
<img width="1228" height="337" alt="image" src="https://github.com/user-attachments/assets/8b606ae4-1c1c-42b2-8c67-7042f67470a0" />
<img width="1226" height="210" alt="image" src="https://github.com/user-attachments/assets/f42d9f33-8302-469b-abf5-6dce981550fe" />
  
---
  
### Шаг 9. Проверка работы
  
Жду пока все инстансы, созданные в прошлом шаге, запустятся:
  
<img width="1893" height="236" alt="image" src="https://github.com/user-attachments/assets/b44af05c-a19b-4c6a-ac57-aa877b2432ba" />
  
Далее, используя публичный адрес `web-server`, октрываю его в браузере. Появляется страница с информацией о PHP:
  
<img width="1915" height="220" alt="image" src="https://github.com/user-attachments/assets/f1a1c70a-6395-405c-8c29-4e91de7901dc" />
  
Подключаюсь к `bastion-host` по SSH и после проверяю подключение к интернету, выполнив ping:
```
ssh -i student-key-k15.pem ec2-user@35.158.123.115
```
  
<img width="1444" height="446" alt="image" src="https://github.com/user-attachments/assets/ba2db293-0304-4078-9e81-c45b0b5443cf" />
  
С `bastion-host` пробую подключиться к `db-server` *(не пробуется)* :
```
mysql -h 10.15.2.143 -u root -p
```
  
<img width="960" height="215" alt="image" src="https://github.com/user-attachments/assets/d2eb9c67-d881-4c3e-8f4a-3b5369d1bafe" />
 
---
  
### Шаг 10. Дополнительные задания. Подключение в приватную подсеть через Bastion Host
  
На своей локальной машине запускаю SSH Agent и проверяю подключение:
```
Start-Service ssh-agent
Get-Service ssh-agent
```
  
<img width="1470" height="221" alt="image" src="https://github.com/user-attachments/assets/24fff586-5730-4ef6-a8d9-3389b8b388a4" />
  
Добавляю ключ в агент и подключаюсь к `bastion-host`:
```
ssh-add student-key-k15.pem
ssh -A ec2-user@63.176.145.215
```
  
<img width="1469" height="471" alt="image" src="https://github.com/user-attachments/assets/336a1c45-0357-44a5-b88c-e552fa4f4254" />
  
Из `bastion-host` перехожу к `db-server`:
```
ssh ec2-user@10.15.2.220
```
<img width="1468" height="520" alt="image" src="https://github.com/user-attachments/assets/ba4476e4-97c6-4056-8b8d-7ad09ce6899f" />
  
Обновляю систему на `db-server`:
```
sudo dnf update -y
```
  
<img width="1469" height="518" alt="image" src="https://github.com/user-attachments/assets/5fcd21a2-2e05-417d-9d9f-84c84731758c" />
  
Устанавливаю htop:
```
sudo dnf install -y htop
```
  
<img width="1467" height="746" alt="image" src="https://github.com/user-attachments/assets/d6d60f38-9a6e-48e2-ba69-2fce03fbf6f8" />
  
Подключаюсь к MySQL серверу:
```
mysql -u root -p
```
  
<img width="1472" height="334" alt="image" src="https://github.com/user-attachments/assets/642da314-4e64-41d9-8a94-bd552052d9d1" />
  
Выхожу из MYSQL и затем из `db-server` и `bastion-host`. На локальном компьютере завершаю работу с SSH Agent:
```
ssh-agent -k
```
  
<img width="1480" height="104" alt="image" src="https://github.com/user-attachments/assets/eca3faf5-2ee8-4dd2-922e-33b9d81fc929" />
  
---
  
### Завершение работы

В последнем шаге выполняю удаление в строгом порядке:
- Удаляю все три инстанса: `web-server`, `bastion-host` и `db-server`:
  
<img width="1900" height="272" alt="image" src="https://github.com/user-attachments/assets/d6e47714-dd90-479a-8693-51a6f63faa20" />
  
- Удаляю `NAT Gateway`:
  
<img width="1900" height="227" alt="image" src="https://github.com/user-attachments/assets/0243f66c-0356-4323-a419-dbe461114d10" />
  
- Удаляю `Security Groups`:
  
<img width="1896" height="158" alt="image" src="https://github.com/user-attachments/assets/c306a2f7-cf5f-4d03-a5b0-adf635e248a1" />
  
- Удаляю `Internet Gateway`:
  
<img width="1892" height="120" alt="image" src="https://github.com/user-attachments/assets/e6a9293f-01a4-460f-92dd-d18b54f085be" />
  
- Удаляю созданную `VPC`:
  
<img width="1894" height="178" alt="image" src="https://github.com/user-attachments/assets/ffabcca9-c48a-4d7f-a75b-1befbeb79f43" />
  
## Заключение
  
В ходе лабораторной работы была создана и настроена облачная сеть (VPC) в AWS, включающая публичную и приватную подсети, интернет-шлюз и NAT Gateway. Настроены таблицы маршрутов и группы безопасности, обеспечивающие безопасное взаимодействие между компонентами. Были развернуты EC2-инстансы — веб-сервер, база данных и bastion host — и проверена их корректная работа. В результате освоены практические навыки построения защищённой сетевой архитектуры и подключения к приватным ресурсам через Bastion Host.
  
## Библиография
  
1. https://github.com/MSU-Courses/cloud-computing/tree/main/_lab/03_Cloud_Networking
2. https://eu-central-1.console.aws.amazon.com/console/home?region=eu-central-1#
3. https://docs.aws.amazon.com/vpc/latest/userguide/what-is-amazon-vpc.html
4. https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/concepts.html
