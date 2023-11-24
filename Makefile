CONTAINER_NAME=bav_app

# Instala a aplicação e sobe o sistema
install:
	make docker-build
	make docker-up
	make docker-clear

# Sobe o sistema
up:
	make docker-up

# Roda os testes unitário.
# Se houver o parâmetro group, então executa apenas o group ex: make docker-test group=test-especifico-test
test:
ifdef group
	docker exec -t $(CONTAINER_NAME) ./vendor/bin/phpunit --group="$(group)" --stop-on-failure
endif
ifdef filter
	docker exec -t $(CONTAINER_NAME) ./vendor/bin/phpunit --filter="$(filter)" --stop-on-failure
else
	docker exec -t $(CONTAINER_NAME) ./vendor/bin/phpunit --stop-on-failure
endif

# Mostra os logs do container
log:
	docker-compose logs -f

# Entra no bash do container
bash:
	docker exec -it $(CONTAINER_NAME) bash

# Limpa os caches, gera as entities e os proxies
clear:
	docker exec $(CONTAINER_NAME) bash  -c "php artisan optimize:clear"

# Builda o container
build:
	docker-compose build

# Sobe o container
up:
	export HOST_IP=localhost; docker-compose up -d

# Derruba o container
down:
	docker-compose down

