pipeline {
    agent { label 'ansible-agent' }

    stages {
        stage('Clone repo') {
            steps {
                git 'https://github.com/your/lab05.git'
            }
        }
        stage('Run Ansible Playbook') {
            steps {
                sh 'ansible-playbook -i ansible/hosts.ini ansible/setup_test_server.yml'
            }
        }
    }
}
