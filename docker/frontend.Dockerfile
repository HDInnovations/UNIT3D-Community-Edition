FROM node:14-alpine
WORKDIR /build
COPY yarn.lock .
COPY package.json webpack.mix.js ./
COPY resources/ ./resources
COPY public/ ./public
RUN yarn install
RUN yarn run production
VOLUME ["/build/public"]