FROM node:14
VOLUME ["/build/static", "/build/node_modules"]
WORKDIR /build
COPY package.json package-lock.json yarn.lock webpack.mix.js ./
COPY resources/ ./resources
RUN yarn install && yarn add cross-env && yarn run production
COPY ./public/files/ ./static/files/
COPY ./public/img/ ./static/img/
COPY ./public/sounds/ ./static/sounds/
COPY ./public/favicon.ico ./public/index.php ./public/robots.txt ./public/web.config ./static/
RUN cp -rv ./public/css/ ./public/fonts/ ./public/js/ ./public/mix-manifest.json ./static/
RUN chmod -Rv 777 /build/static