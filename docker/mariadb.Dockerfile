FROM mariadb:10
COPY docker/*.sql /docker-entrypoint-initdb.d/
RUN ls -la /docker-entrypoint-initdb.d/