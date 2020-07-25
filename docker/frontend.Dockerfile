FROM node:14-alpine
VOLUME ["/build/static", "/build/node_modules"]
WORKDIR /build
COPY yarn.lock .
COPY package.json webpack.mix.js ./
COPY resources/ ./resources
RUN yarn install
RUN yarn add cross-env && yarn run dev
COPY ./public/css/ ./static/css/
COPY ./public/fonts/ ./static/fonts/
COPY ./public/js/ ./static/js/
COPY ./public/mix-manifest.json ./static/mix-manifest.json
COPY ./public/mix-sri.json ./static/mix-sri.json
COPY ./public/files/ ./static/files/
COPY ./public/img/ ./public/img/
COPY ./public/sounds/ ./static/sounds/
COPY ./public/favicon.ico ./public/index.php ./public/robots.txt ./public/web.config ./static/
