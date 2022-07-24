ifeq (, $(shell which docker-compose))
 $(error "docker-compose not found, consider doing apt-get install docker-compose")
endif
build:
	docker-compose up -d
test:
	php ./vendor/bin/phpunit tests/
	docker-compose exec imageservice sh -c "php ./vendor/bin/phpunit /var/www/html/image/tests"
