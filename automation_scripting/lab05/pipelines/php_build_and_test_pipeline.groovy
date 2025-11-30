pipeline {
    agent { label 'php-ssh-agent' }

    environment {
        REPO_URL = 'git@github.com:YOUR_USERNAME/YOUR_PHP_REPO.git'
        BRANCH   = 'main'
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: "${BRANCH}", url: "${REPO_URL}"
            }
        }

        stage('Install dependencies') {
            steps {
                sh 'composer install --no-interaction --no-progress'
            }
        }

        stage('Run tests') {
            steps {
                sh './vendor/bin/phpunit --testdox'
            }
        }
    }

    post {
        success {
            echo 'PHP build & tests passed'
        }
        failure {
            echo 'PHP build or tests failed'
        }
    }
}
