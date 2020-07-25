.PHONY: docker config

all: stop clean config build install start

current_dir := $(shell pwd)

build: build_frontend build_app

config:
	@./configure_docker.sh

install:
	@docker-compose -p unit3d up --remove-orphans -d mariadb redis
	@echo "Sleeping w/maria for 30s"
	@sleep 30  # Sleep so mariadb has enough time to restart itself
	@docker-compose -p unit3d exec mariadb mysql -hmariadb -u root -punit3d -e "CREATE USER IF NOT EXISTS'unit3d'@'%';GRANT ALL PRIVILEGES ON * . * TO 'unit3d'@'%';FLUSH PRIVILEGES;CREATE DATABASE IF NOT EXISTS unit3d CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';"
	# Install the deps and configure laravel
	@UID=$$UID GID=$$GID docker-compose -p unit3d run --rm app ./docker_setup.sh

shell_app:
	@docker-compose -p unit3d exec app bash

shell_http:
	@docker-compose -p unit3d exec http ash

sql:
	@docker-compose -p unit3d run --rm mariadb mysql -hmariadb -u root -punit3d -D unit3d

build_frontend:
	#@docker-compose -p unit3d build frontend
	@yarn install
	@yarn run production

build_app:
	@UID=$$UID GID=$$GID docker-compose -p unit3d build app

build_http:
	@docker-compose -p unit3d build http

build_redis:
	@docker-compose -p unit3d build redis

build_mariadb:
	@docker-compose -p unit3d build mariadb

build_echo:
	@docker-compose -p unit3d build echo-server

build_portainer:
	@docker-compose -p unit3d build portainer

up:
	@UID=$$UID GID=$$GID docker-compose -p unit3d up --build --remove-orphans

start:
	@docker-compose -p unit3d up --remove-orphans -d

stop:
	@docker-compose -p unit3d down

clean:
	@rm -rf public/css public/fonts public/js public/mix-manifest.json public/mix-sri.json

cleanall: clean
	@rm -rf docker/Caddyfile docker/env

docker_prune:
	@docker system prune --volumes