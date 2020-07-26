# Docker Setup

You have two options when deciding to use docker.

1. Use the docker-compose method (recommended).
2. Build our image and provide the dependencies yourself

## Docker

Installation of docker is out of scope for these instructions, but it generally
will be as simple as the following for ubuntu based systems:

    sudo apt install docker docker-compose
    sudo systemctl enable docker
    sudo systemctl start docker

## docker-compose.yml

For a quick-start setup simply run `make` from the project root.

    make
    
This will ask a couple questions and then configure and start the services.
    
If you receive an error related to not being able to connect to mariadb, run the command
again. The mariadb instance may not have been fully initialized yet.

Connect to a mysql shell

    make sql
    
Connect to one of the services shells

    make shell_{app,http,mariadb,echo-server}
    
Start the services

    make start
    
Stop the services

    make stop
    

## Build Image

You can build the image `unit3d_app:latest` via:

    make app