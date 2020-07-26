#1/bin/bash
stack_name="unit3d"
compose_file="docker/docker-compose.yml"
uid=$(id -r -u)
gid=$(id -r -g)

run_build () {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name build;
}

run_config () {
  UID=${uid} GID=${gid} ./docker/configure_docker.sh;
}

run_install () {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name up --remove-orphans -d mariadb redis
  echo "Sleeping w/maria for 30s"
  sleep 5  # Sleep so mariadb has enough time to restart itself
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name exec mariadb mysql -hmariadb -u root -punit3d -e "CREATE USER IF NOT EXISTS'unit3d'@'%';GRANT ALL PRIVILEGES ON * . * TO 'unit3d'@'%';FLUSH PRIVILEGES;CREATE DATABASE IF NOT EXISTS unit3d CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';"
  # Install the deps and configure laravel
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name run --rm app ./docker_setup.sh
}

run_clean_config () {
  rm -rf docker/Caddyfile \
    docker/env
}

run_sql () {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name run --rm mariadb mysql -hmariadb -u root -punit3d -D unit3d
}

run_redis() {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name exec redis redis-cli
}

run_clean () {
  rm -rf docker/Caddyfile \
    docker/env \
    public/css \
    public/fonts \
    public/js \
    public/mix-manifest.json \
    public/mix-sri.json \
    bootstrap/cache/*.php
}

run_prune() {
  docker system prune --volumes;
}

run_usage() {
  echo "Usage: $0 {config|install|build|start|stop|sql}"
  exit 1
}

run_shell_app () {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name exec app bash
}

run_shell_http () {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name exec http ash
}

run_up() {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name up --remove-orphans -d
}

run_down() {
  UID=${uid} GID=${gid} docker-compose -f $compose_file -p $stack_name down
}

case "$1" in
  build)
    run_build
    ;;
  config)
    run_config
    ;;
  install)
    run_install
    ;;
  clean)
    run_clean
    ;;
  cleanall)
    run_clean
    run_clean_config
    ;;
  prune)
    run_prune
    ;;
  redis)
    run_redis
    ;;
  shell_app)
    run_shell_app
    ;;
  shell_http)
    run_shell_http
    ;;
  sql)
    run_sql
    ;;
  *)
  run_usage
  ;;
esac


