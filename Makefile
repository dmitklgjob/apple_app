up: docker-up
down: docker-down
restart: docker-down docker-up
init-dev: docker-init-dev yii2-init app-init
docker-init-dev: docker-pull docker-build docker-up
app-init: composer-install wait-postgres migrations
migrations: migrations-dev migrations-test

yii2-init:
	docker-compose run --rm php-cli php init --env=Development

migrations-dev:
	docker-compose run --rm php-cli php yii migrate/up --interactive=0

migrations-test:
	docker-compose run --rm php-cli php yii_test migrate/up --interactive=0

wait-postgres:
	docker-compose run --rm --no-deps php-cli wait-for-it postgres:5432 -t 60

composer-install:
	docker-compose run --rm --no-deps php-cli composer install

docker-build:
	docker-compose build

docker-pull:
	docker-compose pull

docker-up:
	docker-compose up -d

docker-down-with-volumes:
	docker-compose down --remove-orphans -v

docker-down:
	docker-compose down --remove-orphans

docker-stop:
	docker-compose stop

add-hosts:
	echo '127.0.0.1    backend.apple.loc     frontend.apple.loc' | sudo tee -a /etc/hosts > /dev/null

php-bash: ## Connects to bash of php-container
	docker-compose exec php-cli bash

tests:
	docker-compose run --rm php-cli vendor/bin/codecept run
