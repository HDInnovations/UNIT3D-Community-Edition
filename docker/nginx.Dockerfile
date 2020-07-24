FROM node:14-alpine AS ui
WORKDIR /build
COPY yarn.lock .
COPY package.json webpack.mix.js ./
COPY resources/ ./resources
COPY public/ ./public
RUN yarn install
RUN yarn run production

FROM nginx:latest
WORKDIR /app
ARG APP_HOSTNAME
COPY docker/fastcgi.conf /etc/nginx/fastcgi.conf
COPY docker/fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
RUN sed -i -r "s/APP_HOSTNAME/${APP_HOSTNAME}/g" /etc/nginx/conf.d/default.conf
COPY public ./
COPY --from=ui /build/public/css ./public/
COPY --from=ui /build/public/fonts ./public/
COPY --from=ui /build/public/js ./public/
COPY --from=ui /build/public/mix-manifest.json ./public/mix-manifest.json

