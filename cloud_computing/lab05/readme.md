# Лабораторная работа №5. Облачные базы данных. Amazon RDS, DynamoDB

## Цель
Целью работы является ознакомиться с сервисами Amazon RDS (Relational Database Service) и Amazon DynamoDB, а также научиться:
- Создавать и настраивать экземпляры реляционных баз данных в облаке AWS с использованием Amazon RDS.
- Понимать концепцию Read Replicas и применять их для повышения производительности и отказоустойчивости баз данных.
- Подключаться к базе данных Amazon RDS с виртуальной машины EC2 и выполнять базовые операции с данными (создание, чтение, обновление, удаление записей - CRUD).
- (Дополнительно) Ознакомиться с сервисом Amazon DynamoDB и освоить работу с хранением данных в NoSQL-формате.

## Ход работы

### Шаг 1. Подготовка среды (VPC/подсети/SG)

Находясь в AWS Console, перехожу в `VPC` и создаю `VPC and more` с двумя публичными и двумя приватными подсетями. Заполняю настройки: 
- VPC name: project-vpc
- IPv4 CIDR: 10.0.0.0/16  
  
![alt text](images/image.png)
  
- Number of AZs: 2
- Public subnets: 2
- Private subnets: 2
- Subnet CIDR:
    - 10.0.1.0/24 - public AZ1
    - 10.0.2.0/24 - public AZ2
    - 10.0.3.0/24 - private AZ1
    - 10.0.4.0/24 - private AZ2
- NAT gateways: 1 per AZ  
  
![alt text](images/image-1.png)
  
VPC со всеми необходимыми подсетями успешно создан:  
![alt text](images/image-2.png)
  
Далее захожу в `EC2` -> `Security Groups` и создаю группу, заполняя следующие пункты:
- Name: web-security-group
- VPC: project-vpc

![alt text](images/image-3.png)
- Inbound Rules
    - HTTP (порт 80) от любого источника;
    - SSH (порт 22) от моего IP-адреса;
- Outbound Rules
    - Custom TCP -> Anywhere;

![alt text](images/image-4.png)
  
Затем создаю еще одну `Security Group`:
- Name: db-mysql-security-group
- VPC: project-vpc
  
![alt text](images/image-5.png)
- Inbound Rules:
    - MySQL/Aurora (порт 3306) от web-security-group;
- Outbound Rules:
    - All traffic -> Anywhere;
  
![alt text](images/image-6.png)
  
Меняю "web-security-group", добавляя дополнительное правило для исходящего трафика:
- Outbound Rules:
    - MySQL/Aurora (порт 3306) к db-mysql-security-group

![alt text](images/image-7.png)
  
---
  
### Шаг 2. Развертывание Amazon RDS

 > **Что такое Subnet Group? И зачем необходимо создавать Subnet Group для базы данных?** Subnet Group - это набор приватных подсетей в одном VPC, из которых RDS выбирает сеть, где будет размещена база данных. Она нужна, чтобы база работала только в приватных подсетях и была недоступна из интернета.
  
Перехожу в сервис `Aurora and RDS.` -> `Subnet groups` и нажимаю `Create DB subnet group`. Заполняю поля:
- Name: project-rds-subnet-group
- VPC: project-vpc
  
![alt text](images/image-8.png)
  
Добавляю 2 приватные подсети из 2 разных AZ:  
![alt text](images/image-9.png)


![alt text](images/image-10.png)
![alt text](images/image-11.png)
![alt text](images/image-12.png)
![alt text](images/image-13.png)
![alt text](images/image-14.png)
![alt text](images/image-15.png)
![alt text](images/image-16.png)
![alt text](images/image-17.png)
![alt text](images/image-18.png)
![alt text](images/image-19.png)
![alt text](images/image-20.png)