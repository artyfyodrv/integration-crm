IMAGE := application-backend

# Запускаем локально проект
serve:
	composer run --timeout=0 serve

# запускаем localtunnel
start:
	lt --port 80 --subdomain artyom --print-requests --allow-invalid-cert

# подтягивает команды composer, сбор образа docker, и контейнеры
up: install build
	docker-compose up -d

# подтягивает пакеты и устанавливает зависимости
# пересобирает автозагрузчик, включает режим разработки

install:
	composer install
	composer dump-autoload
	composer development-enable
# собирает образ
build:
	docker build -t $(IMAGE) .
