FROM nginx:latest
WORKDIR /app
ARG APP_HOSTNAME
COPY docker/fastcgi.conf /etc/nginx/fastcgi.conf
COPY docker/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN sed -i -r "s/APP_HOSTNAME/${APP_HOSTNAME}/g" /etc/nginx/conf.d/default.conf
COPY public/mix-manifest.json ./public/mix-manifest.json
COPY public/ ./public/

