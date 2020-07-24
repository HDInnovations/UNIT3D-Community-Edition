deps:
	@yarn install
	@composer install

frontend:
	@yarn run development

image:
	@docker build -t unit3d:latest -f docker/Dockerfile .

up:
	@docker-compose up --build