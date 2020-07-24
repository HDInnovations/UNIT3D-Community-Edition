install:
	yarn install
	yarn run dev
	docker-compose up -d mariadb && sleep 10 #Sleep so mariadb has enough time to restart itself
	docker exec -it united_mariadb_1 mysql -u root -e "CREATE USER IF NOT EXISTS'unit3d'@'%';GRANT ALL PRIVILEGES ON * . * TO 'unit3d'@'%';FLUSH PRIVILEGES;CREATE DATABASE IF NOT EXISTS unit3d CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';"
	docker container run -it --network united_unit3d --mount source=united_composer_cache,target=/app/vendor --mount source=united_storage,target=/app/storage  united_app ./docker_setup.sh
	docker-compose down

shell_app:
	docker container run -it --network united_unit3d --mount source=united_composer_cache,target=/app/vendor --mount source=united_storage,target=/app/storage united_app bash

shell_mariadb:
	docker exec -it united_mariadb_1 mysql -u root

shell_http:
	docker exec -it united_http_1 bash

mariadb:
	docker run -it --network united_unit3d --rm mariadb mysql -hmariadb -uroot

image:
	docker build -t unit3d:latest -f docker/Dockerfile .

up:
	docker-compose up --build

start:
	docker-compose up

stop:
	docker-compose down