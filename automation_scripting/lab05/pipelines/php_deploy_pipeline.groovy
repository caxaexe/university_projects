pipeline {
    agent { label 'ansible-agent' }

    environment {
        REPO_URL = 'git@github.com:YOUR_USERNAME/YOUR_PHP_REPO.git'
        BRANCH   = 'main'
        APP_DIR  = '/var/www/phpapp'
        SSH_USER = 'ansible'
        SSH_HOST = 'test-server'
    }

    stages {
        stage('Checkout PHP project') {
            steps {
                git branch: "${BRANCH}", url: "${REPO_URL}"
            }
        }

        stage('Archive project') {
            steps {
                sh 'tar czf app.tar.gz .'
            }
        }

        stage('Upload & deploy to test server') {
            steps {
                sshagent(credentials: ['ansible-ssh-key']) {
                    sh '''
                    scp -o StrictHostKeyChecking=no app.tar.gz ${SSH_USER}@${SSH_HOST}:/tmp/app.tar.gz

                    ssh -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_HOST} << 'EOF'
                      sudo mkdir -p ${APP_DIR}
                      sudo tar xzf /tmp/app.tar.gz -C ${APP_DIR}
                      sudo chown -R www-data:www-data ${APP_DIR}
                      sudo systemctl restart apache2
                    EOF
                    '''
                }
            }
        }
    }

    post {
        success {
            echo 'PHP project deployed to test server'
        }
        failure {
            echo 'Deploy failed'
        }
    }
}
