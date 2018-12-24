#!/usr/bin/env groovy

node('master') {
    try {
        stage('build') {
            git url: 'git@github.com:alexrusin/sdforum.git'

            // Start services (Let docker-compose build containers for testing)
            sh "chmod u+x develop"
            sh "./develop up -d"

            // Get composer dependencies
            sh "./develop composer install"

            // Create .env file for testing
            sh '/var/lib/jenkins/.venv/bin/aws s3 cp s3://shippingdocker-secrets/env-ci .env'
            sh './develop art key:generate'
        }

        stage('test') {
            sh "APP_ENV=testing ./develop test"
        }

        if( env.BRANCH_NAME == 'master' ) {
            stage('package') {
                sh 'chmod u+x docker/build'
                sh './docker/build'
            }
        }
    } catch(error) {
        // Maybe some alerting?
        throw error
    } finally {
        // Spin down containers no matter what happens
        sh './develop down'
    }
}
