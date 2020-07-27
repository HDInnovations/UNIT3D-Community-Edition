FROM mariadb:10
COPY docker/*.sql /docker-entrypoint-initdb.d/