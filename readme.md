PhotoPick
=========

[![pipeline status](https://gitlab.com/goodagency/photopick/badges/master/pipeline.svg)](https://gitlab.com/goodagency/photopick/commits/master)

## Install

	yarn install
	composer install

## Docker

	docker-compose up
	docker-compose down

## GitLab runner

	gitlab-runner exec docker phpunit:7.3
	gitlab-runner exec docker phpstan:7.3
	gitlab-runner exec docker code_standard:7.3

## Run tests

	vendor/bin/phpunit --no-configuration tests
	composer t
	vendor/bin/phpstan analyse app -l 7 --memory-limit=512M
	comsposer sa

## Code standard

	# Install
	composer create-project nette/coding-standard temp/nette-coding-standard
	
	# Run test
	composer cs-t
	
	# Run fix
	composer cs-f

## Cron

	php www/index.php Cron:Index:run
	php www/index.php Cron:Index:search
	php www/index.php Cron:Index:palette
	php www/index.php Cron:Storage:run
	php www/index.php Cron:Storage:clean
	php www/index.php Cron:Storage:thumbnails
