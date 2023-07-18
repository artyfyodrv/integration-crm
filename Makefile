IMAGE := application-backend

# Запускаем локально проект
serve:
	composer run --timeout=0 serve

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


