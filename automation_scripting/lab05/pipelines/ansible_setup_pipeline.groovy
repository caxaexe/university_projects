pipeline {
    agent { label 'ansible-agent' } 
    
    environment {
        REPO_URL = 'git@github.com:YOUR_USERNAME/YOUR_PHP_REPO.git'  // или отдельный репо с ansible
        BRANCH   = 'main'
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: "${BRANCH}", url: "${REPO_URL}"
            }
        }

        stage('Run Ansible playbook') {
            steps {
                dir('ansible') {
                    sh '''
                      ansible-playbook -i hosts.ini setup_test_server.yml
                    '''
                }
            }
        }
    }

    post {
        success {
            echo 'Test server configured via Ansible'
        }
        failure {
            echo 'Ansible setup failed'
        }
    }
}
