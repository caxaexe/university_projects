pipeline {
    agent { label 'ansible-agent' }

    stages {
        stage('Clone PHP project') {
            steps {
                git 'https://github.com/your/php-project.git'
            }
        }
        stage('Copy files to server') {
            steps {
                sh 'scp -i ~/.ssh/test_server_key -r ./test-server:/var/www/html/project'
            }
        }
        stage('Configure (via Ansible)') {
            steps {
                sh 'ansible-playbook -i ansible/hosts.ini ansible/setup_test_server.yml'
            }
        }
    }
}
