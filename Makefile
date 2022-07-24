ifeq (, $(shell which docker-compose))
 $(error "docker-compose not found, consider doing apt-get install docker-compose")
endif
build:
	echo "UID="$(shell id -u) > .env
	echo "GID="$(shell id -g) >> .env
	docker-compose up -d
	docker-compose exec --user "$(shell id --user):$(shell id --group)" imageservice sh -c "composer install"
test:
	php ./vendor/bin/phpunit tests/
	docker-compose exec imageservice sh -c "php ./vendor/bin/phpunit /var/www/html/image/tests"
