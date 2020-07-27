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
    
This command only needs to be run once unless you have custom builds. Once this completes
you should have a fully deployed environment working.

Once you see some logs starting with `http_` doing acme configurations you have completed the startup. Its safe to
ctrl+c out of this as its just tailing the log files via `./docker.sh logs -f`. You can always run this command 
too see the current log output in real time.
    
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

Using `artisan` commands. These all run under the app container:

    ./docker.sh artisan cache:clear
    Application cache cleared!

Connect to a mysql shell `./docker.sh sql`:

    ./docker.sh sql
    Reading table information for completion of table and column names
    You can turn off this feature to get a quicker startup with -A
    
    Welcome to the MariaDB monitor.  Commands end with ; or \g.
    Your MariaDB connection id is 7
    Server version: 10.5.4-MariaDB-1:10.5.4+maria~focal mariadb.org binary distribution
    
    Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.
    
    Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.
    
    MariaDB [unit3d]> 

    
Connect to one of the services shells:

    ./docker.sh run {app,http,mariadb,echo-server} bash    
     or 
    ./docker.sh run {app,http,mariadb,echo-server} ash
    
Start all the services (foreground)
    
    ./docker.sh up

Start all the services (background)

    ./docker.sh up -d
    
Start a single service

    ./docker.sh mariadb up -d
    
Stop the services

    ./docker.sh down
    
## Build Image

You can build the image `unit3d_app:latest` via:

    ./docker.sh build [app,frontend,mariadb]
    
If no argument specified, builds all containers.