#1/bin/bash
stack_name="unit3d"
compose_file="docker-compose.yml"

run_build_app () {
  docker-compose -f $compose_file -p $stack_name build app;
}

run_build_frontend () {
  docker-compose -f $compose_file -p $stack_name build frontend;
}

run_build () {
  docker-compose -f $compose_file -p $stack_name build;
}

run_config () {
  ./docker/configure_docker.sh;
}


run_install () {
  if ! test -f docker/Caddyfile; then
    run_config
  fi
  docker-compose -f $compose_file -p $stack_name up -d redis mariadb
  echo "Sleeping w/maria for 10s"
  sleep 10  # Sleep so mariadb has enough time to restart itself
  # Install the deps and configure laravel
  docker-compose -f $compose_file -p $stack_name run --rm app ./docker_setup.sh
}

run_clean_config () {
  rm -rf docker/Caddyfile \
    docker/env
}

run_sql () {
  docker-compose -f $compose_file -p $stack_name run --rm mariadb mysql -hmariadb -u root -punit3d -D unit3d
}

run_redis() {
  docker-compose -f $compose_file -p $stack_name exec redis redis-cli
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
  echo "Usage: $0 {config|install|build|start|stop|sql|up|down}"
  exit 1
}

run_shell_app () {
  docker-compose -f $compose_file -p $stack_name exec app bash
}

run_shell_http () {
  docker-compose -f $compose_file -p $stack_name exec http ash
}

run_up() {
  docker-compose -f $compose_file -p $stack_name up --remove-orphans -d
}

run_down() {
  docker-compose -f $compose_file -p $stack_name down
}

case "$1" in
  build)
    run_build
    ;;
  build_app)
    run_build_app
    ;;
  build_frontend)
    run_build_frontend
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
  up)
    run_up
    ;;
  down)
    run_down
    ;;
  sql)
    run_sql
    ;;
  run)
    shift
    # shellcheck disable=SC2068
    docker-compose -f $compose_file -p $stack_name run --rm $@
    ;;
  *)
  run_usage
  ;;
esac
