FROM node:14
VOLUME ["/build/static", "/build/node_modules"]
WORKDIR /build
COPY package.json package-lock.json yarn.lock webpack.mix.js ./
COPY resources/ ./resources
COPY public/ ./public
COPY docker/build_frontend.sh .
ENTRYPOINT ["./build_frontend.sh"]
#RUN chmod -Rv 777 /build/static