# Docker Setup

You have two options when deciding to use docker.

1. Use the docker-compose method (recommended).
2. Build our image and provide the dependencies yourself

## Docker

Installation of docker is out of scope for these instructions, but it generally
will be as simple as the following for ubuntu based systems:

    sudo apt install docker
    sudo systemctl enable docker
    sudo systemctl start docker

## docker-compose.yml

Setup docker dependencies. This will create the database & user then do the 
initial database migration seed.

    make install
    
Run the swarm:

    make start
    

## Build Image

You can build the image `unit3d:latest` via::

    make image