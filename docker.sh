#1/bin/bash
stack_name="unit3d"
compose_file="docker-compose.yml"

run_config() {
  ./docker/configure_docker.sh
}

run_install() {
  if ! test -f docker/Caddyfile; then
    run_config
  fi
  docker-compose -f ${compose_file} -p ${stack_name} build
  docker-compose -f ${compose_file} -p ${stack_name} up -d mariadb
  echo "Please wait while the database initializes, could take over a minute on slower hardware"
  retval=-1
  until [ $retval -eq 0 ]
  do
    docker-compose -f ${compose_file} -p ${stack_name} exec mariadb mysql -uunit3d -punit3d -D unit3d -s -e "SELECT 1" > /dev/null 2>&1
    retval=$?
    printf "."
    sleep 2
   done
   echo ""
   docker-compose -f ${compose_file} -p ${stack_name} up -d
   docker-compose -f ${compose_file} -p ${stack_name} logs -f
}

run_clean_config () {
  rm -rf docker/Caddyfile \
    docker/env
}

run_sql () {
  docker-compose -f ${compose_file} -p ${stack_name} up -d mariadb
  docker-compose -f ${compose_file} -p ${stack_name} exec mariadb mysql -uunit3d -punit3d -D unit3d
}

run_redis() {
  docker-compose -f ${compose_file} -p ${stack_name} up -d redis
  docker-compose -f ${compose_file} -p ${stack_name} exec redis redis-cli
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
  docker system prune --volumes
}

run_up() {
  docker-compose -f $compose_file -p $stack_name up --remove-orphans -d
}

run_down() {
  docker-compose -f $compose_file -p $stack_name down
}

run_usage() {
  echo "Usage: $0 {artisan|build|clean|cleanall|config|down|exec|install|logs|prune|redis|run|sql}"
  exit 1
}

case "$1" in
  artisan)
    shift
    docker-compose -f $compose_file -p $stack_name exec app php artisan "$@"
    ;;
  build)
    shift
    docker-compose -f $compose_file -p $stack_name build "$@"
    ;;
  clean)
    run_clean
    ;;
  cleanall)
    run_clean
    run_clean_config
    ;;
  config)
    run_config "$2" "$3"
    ;;
  down)
    run_down
    ;;
  exec)
    shift
    docker-compose -f $compose_file -p $stack_name exec "$@"
    ;;
  install)
    run_install
    ;;
  logs)
    shift
    docker-compose -f $compose_file -p $stack_name logs "$@"
    ;;
  prune)
    run_prune
    ;;
  redis)
    run_redis
    ;;
  run)
    shift
    docker-compose -f $compose_file -p $stack_name run --rm "$@"
    ;;
  sql)
    run_sql
    ;;
  up)
    shift
    docker-compose -f $compose_file -p $stack_name up -d "$@"
    docker-compose -f $compose_file -p $stack_name logs -f
    ;;
  *)
    run_usage
    ;;
esac