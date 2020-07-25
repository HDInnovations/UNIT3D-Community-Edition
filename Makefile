.PHONY: docker

install: frontend
	docker-compose up --remove-orphans -d mariadb && sleep 10 #Sleep so mariadb has enough time to restart itself
	docker exec -it united_mariadb_1 mysql -u root -e "CREATE USER IF NOT EXISTS'unit3d'@'%';GRANT ALL PRIVILEGES ON * . * TO 'unit3d'@'%';FLUSH PRIVILEGES;CREATE DATABASE IF NOT EXISTS unit3d CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';"
	docker container run -it --network united_unit3d --mount source=united_composer_cache,target=/app/vendor --mount source=united_storage,target=/app/storage --mount source=united_public,target=/app/public --mount source=united_public_files,target=/app/public/files  united_app ./docker_setup.sh
	docker-compose down

shell_app:
	docker container run -it --network united_unit3d --mount source=united_composer_cache,target=/app/vendor --mount source=united_storage,target=/app/storage united_app bash

shell_mariadb:
	docker exec -it united_mariadb_1 mysql -u root

shell_http:
	docker exec -it united_http_1 sh

sql:
	docker run -it --network united_unit3d --rm mariadb mysql -hmariadb -uunit3d

frontend:
	docker-compose build frontend

image:
	docker build -t unit3d:latest -f docker/Dockerfile .

up:
	docker-compose up --build --remove-orphans

start:
	docker-compose up --remove-orphans

stop:
	docker-compose down

docker_delete_all:
	docker system prune --volumes