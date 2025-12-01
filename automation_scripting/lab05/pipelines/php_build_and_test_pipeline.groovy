pipeline {
    agent { label 'ssh-agent' }

    stages {
        stage('Checkout') {
            steps {
                echo 'Cloning repository...'
                checkout scm
            }
        }

        stage('Install Composer Dependencies') {
            steps {
                echo 'Installing Composer dependencies...'
                sh 'composer install'
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running PHPUnit tests...'
                sh './vendor/bin/phpunit --testdox'
            }
        }
    }

    post {
        always {
            echo 'Pipeline completed.'
        }
        success {
            echo 'Build & Test pipeline finished successfully!'
        }
        failure {
            echo 'Pipeline failed — check test logs.'
        }
    }
}
