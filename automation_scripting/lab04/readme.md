# Лабораторная работа №4. Настройка Jenkins для автоматизации задач DevOps

## Цель
Узнать, как настроить Jenkins для автоматизации задач DevOps, включая создание и управление конвейерами CI/CD.

## Ход работы

Создаю проект со следующей структурой:
```
lab04/
│── docker-compose.yml
│── Dockerfile
│── .env
│── secrets/
│     ├── jenkins_agent_ssh_key
│     └── jenkins_agent_ssh_key.pub
```

Перехожу к созданию `Docker Compose` для определения в нем служб контроллера Jenkins и SSH-агент:
```yml
version: '3.9'

services:
  jenkins-controller:
    image: jenkins/jenkins:lts
    container_name: jenkins-controller
    ports:
      - "8080:8080"
      - "50000:50000"
    volumes:
      - jenkins_home:/var/jenkins_home
    networks:
      - jenkins-network

  ssh-agent:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: ssh-agent
    environment:
      - JENKINS_AGENT_SSH_PUBKEY=${JENKINS_AGENT_SSH_PUBKEY}
    volumes:
      - jenkins_agent_volume:/home/jenkins/agent
    depends_on:
      - jenkins-controller
    networks:
      - jenkins-network

volumes:
  jenkins_home:
  jenkins_agent_volume:

networks:
  jenkins-network:
    driver: bridge
```

Далее создаю `Dockerfile` для для SSH-агента:
```Dockerfile
FROM jenkins/ssh-agent

RUN apt-get update && apt-get install -y php-cli && rm -rf /var/lib/apt/lists/*
```

Перейдя в директорию `secrets` генерирую SSH ключи - приватный и публичный командой
```cmd
ssh-keygen -t rsa -b 4096 -f jenkins_agent_ssh_key -N ""
```
<img width="1412" height="421" alt="Снимок экрана 2025-11-04 184017" src="https://github.com/user-attachments/assets/71b28b71-5eae-4cc8-bdd4-5bdb8a0c9c2a" />
  
В корне проекта создаю `.env` со своим публичным ключом, который сгенерировался ранее:
```env
JENKINS_AGENT_SSH_PUBKEY=ssh-rsa AAAA...МОЙПУБЛИЧНЫЙКЛЮЧ...
```

Собираю образ и запускаю в фоновом режиме Jenkins + SSH Agent:
```cmd
docker-compose up -d --build
```
<img width="1433" height="907" alt="Снимок экрана 2025-11-04 192704" src="https://github.com/user-attachments/assets/ff94d5e2-9118-43cc-9070-8ba68ac088cb" />
  
Проверяю все ли образы запущены:
```cmd
docker ps
```
<img width="1467" height="203" alt="Снимок экрана 2025-11-04 192710" src="https://github.com/user-attachments/assets/682d173a-2618-4c04-bdfc-2f8fd3341de2" />
  
Перехожу по URL `http://localhost:8080` и попадаю в веб-интерфейс Jenkins, где для его разблокировки требуется ввести админский пароль, поэтому в cmd использую команду, чтобы получить пароль :
```cmd
docker exec -it jenkins-controller cat /var/jenkins_home/secrets/initialAdminPassword
```
<img width="1437" height="73" alt="Снимок экрана 2025-11-04 192751" src="https://github.com/user-attachments/assets/1fbcfc9f-7482-4a64-ad23-97fad81d1bcf" />
  
Ввожу полученный админский пароль:
<img width="1258" height="838" alt="Снимок экрана 2025-11-04 192809" src="https://github.com/user-attachments/assets/1d34d42a-e727-418c-b0bc-dcec074d47c3" />
  
Начинается загрузка всех важных компонентов:
<img width="1264" height="837" alt="Снимок экрана 2025-11-04 193158" src="https://github.com/user-attachments/assets/668bbf0e-f649-45b0-a3c5-5796130011b8" />
  
Создаю админ-пользователя, заполняя все необходимые поля:
<img width="1133" height="844" alt="Снимок экрана 2025-11-04 193247" src="https://github.com/user-attachments/assets/04adcb13-2e74-4368-b15a-a24fad248c1a" />
  
**Jenkins is ready!**
<img width="1136" height="840" alt="Снимок экрана 2025-11-04 193318" src="https://github.com/user-attachments/assets/15936bb6-157c-4a41-a130-2e7977f54161" />
  
В веб-интерфейсе перехожу во вкладку `Настроить Jenkins` -> `Credentials` -> `Global credentials`, заполнив поля таким образом:
- Kind: SSH Username with private key
- Username: jenkins
- Private Key - Enter directly: secrets/jenkins_agent_ssh_key
<img width="1885" height="847" alt="Снимок экрана 2025-11-04 193455" src="https://github.com/user-attachments/assets/c784b989-a032-48f3-a105-740345d0dc6e" />
<img width="1888" height="841" alt="Снимок экрана 2025-11-04 193540" src="https://github.com/user-attachments/assets/6d6150cd-0a4e-4092-8263-389c43a7fa64" />
  
Далее перехожу к созданию узлу `Настроить Jenkins` -> `Узлы` -> `Новый узел`:  
 - Название узла: ssh-agent1
 - Type: Постоянный агент
<img width="1909" height="525" alt="Снимок экрана 2025-11-04 193611" src="https://github.com/user-attachments/assets/92098afd-d28c-47fc-b4cb-956f99ab1b89" />

Настраиваю созданный узел ранее:
 - Имя: ssh-agent1
 - Колво процессов-исполнителей: 1
 - Удаленная корневая директория: /home/jenkins/agent
 - Метки: php-agent
<img width="1874" height="852" alt="Снимок экрана 2025-11-04 193635" src="https://github.com/user-attachments/assets/cee7f5c6-9ef6-42dd-a6a7-b75280b3149d" />
  
 - Способ запуска: Launch agents via SSH
 - Host: ssh-agent
 - Credentials: jenkins
<img width="1878" height="848" alt="Снимок экрана 2025-11-04 193704" src="https://github.com/user-attachments/assets/8e63f5df-2cbe-4c3e-b24f-6ca7aeff8ce0" />
  
И обязательно меняю поле `Host Key Verification` на ручную проверку:
<img width="1276" height="159" alt="Снимок экрана 2025-11-04 195824" src="https://github.com/user-attachments/assets/f5e8a481-dd08-4f1a-af23-8e1808cfc027" />
  
Создаю новый конвейер, введя имя и выбрав тип Pipeline:
<img width="1886" height="854" alt="Снимок экрана 2025-11-04 195210" src="https://github.com/user-attachments/assets/ef3ff1e3-ab2a-4f1f-a60b-79cce20ce68d" />
  
В своем [PHP-проекте](https://github.com/caxaexe/university_projects/blob/main/sawm/lab04/Jenkinsfile) создаю `Jenkinsfile`, который подключается к агенту, готовит проект, запускает тесты и пишет результат выполнения:
```jenkinsfile
pipeline {
    agent { label 'php-agent' }

    stages {
        stage('Checkout') {
            steps {
                echo 'Cloning repository...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing dependencies...'
            }
        }

        stage('Test') {
            steps {
                echo 'Running PHP tests...'
                sh 'php -v'
                sh 'echo "Simulated tests passed!"'
            }
        }
    }

    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            echo 'All stages completed successfully!'
        }
        failure {
            echo 'Pipeline failed — please check logs.'
        }
    }
}
```
  
В разделе `Pipeline`:
 - Definition: Pipeline script from SCM
 - SCM: Git
 - Repository URL: указываю ссылку на свой репозиторий
<img width="1879" height="846" alt="Снимок экрана 2025-11-04 195410" src="https://github.com/user-attachments/assets/3b98c26f-812e-4922-a5b3-e561c56e88d5" />
  
 - Branch: */main
 - Script path: ссылка на файл Jenkins в моем проекте
<img width="1882" height="855" alt="Снимок экрана 2025-11-04 195427" src="https://github.com/user-attachments/assets/c6fe80db-41be-4d82-87a9-b0e47bb7d6f1" />
<img width="1461" height="333" alt="Снимок экрана 2025-11-04 195657" src="https://github.com/user-attachments/assets/5049cfe1-a407-42b9-9a46-9ce2f18b4a33" />

Pipeline успешно создался, пройдя все тесты:
<img width="1902" height="857" alt="Снимок экрана 2025-11-04 201709" src="https://github.com/user-attachments/assets/e828d158-1091-493e-9e1c-35a0d605b5cd" />  
<img width="1912" height="462" alt="Снимок экрана 2025-11-04 201722" src="https://github.com/user-attachments/assets/92e4841d-ad7d-4207-b611-f6c5629796a4" />  
https://github.com/user-attachments/assets/e8e50b4d-0e13-416e-9fe7-91eea5594fb6
Ссылка безопасна честначестна, там видео доказательства, что я не врушка

## Контрольные вопросы
1. **Каковы преимущества использования Jenkins для автоматизации задач DevOps?**
   Позволяет автоматически выполнять сборку, тестирование и деплой.
   Уменьшает количество ручных операций и ускоряет доставку приложений.
   Легко расширяется через плагины и поддерживает различные среды и языки.
  
3. **Какие еще типы агентов Jenkins существуют?**
   - SSH-агенты
   - Docker-агенты и Docker-in-Docker
   - JNLP (Java Web Start) агенты
   - Статические и динамические агенты в Kubernetes
   - Локальные агенты (на самом Jenkins сервере)
  
5. **С какими проблемами вы столкнулись при настройке Jenkins и как вы их решили?**
   Pipeline оооооооочень долго не хотел создаваться, так как ключ не проходил проверку. После тысячи изменений всего подряд было принято решение поменять поле `Host Key Verification` на ручную и тогда настал мир и покой. 


## Вывод
