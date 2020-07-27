# Docker Setup

You have two options when deciding to use docker.

1. Use the docker-compose method (recommended).
2. Build our image and provide the dependencies yourself

## Docker

Installation of docker is out of scope for these instructions, but it generally
will be as simple as the following for ubuntu based systems:
    
    sudo apt install docker-compose -y
    sudo systemctl enable docker
    sudo systemctl start docker
    sudo usermod -aG docker `id -un`
    
## docker-compose.yml

All commands are handled by the `docker.sh` wrapper script.

For a quick-start setup simply run `./docker.sh install` from the project root.

    ./docker.sh install
    
This command only needs to be run once unless you have custom builds.

Then bring up the services.

    ./docker.sh up
    
To see the logs you can run `logs`

    ./docker.sh logs
    ...
    app_1          | Seeded:  MediaLanguagesSeeder (1.78 seconds)
    app_1          | Seeding: ResolutionsTableSeeder
    app_1          | Seeded:  ResolutionsTableSeeder (0.01 seconds)
    app_1          | Database seeding completed successfully.
    app_1          | Configuration cache cleared!
    app_1          | [27-Jul-2020 12:20:59] NOTICE: fpm is running, pid 713
    app_1          | [27-Jul-2020 12:20:59] NOTICE: ready to handle connections

    
This will ask a couple questions and then build and configure the services.

Connect to a mysql shell

    ./docker.sh sql
    
Connect to one of the services shells

    ./docker.sh run {app,http,mariadb,echo-server} bash
    
Start the services

    ./docker.sh up
    
Stop the services

    ./docker.sh down
    

## Build Image

You can build the image `unit3d_app:latest` via:

    ./docker.sh build