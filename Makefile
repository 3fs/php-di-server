COMPOSER_BIN = ./vendor/bin/composer
PHPUNIT_BIN = ./vendor/bin/phpunit
SERVER_PORT ?= 8111
SERVER_PID ?= /tmp/di-server-test

all: _installComposer

.PHONY: qa
qa: qa/unit qa/integration

qa/unit:
	$(PHPUNIT_BIN) -c tests/unit/phpunit.xml

qa/integration:
	@make go
	$(PHPUNIT_BIN) -c tests/integration/phpunit.xml
	@make stop

watch/%:
		watchmedo shell-command -i "./vendor/*;./build/*;./.git/*;./coverage/*" -R -D -c "make $*"

go: stop
	@echo "Starting the server "
	@php -S "0.0.0.0:${SERVER_PORT}" tests/integration/fixture/app.php > /dev/null & echo $$! > "${SERVER_PID}"

stop:
	-@if [ -f ${SERVER_PID} ]; then \
		echo "Stopping the server"; \
		kill `cat ${SERVER_PID}`; \
		rm ${SERVER_PID}; \
	else \
		echo "Server not running"; \
	fi;

_installComposer:
	mkdir -p ./vendor/bin
	curl -sS https://getcomposer.org/installer | php -- --install-dir=./vendor/bin --filename=composer
	./vendor/bin/composer install
