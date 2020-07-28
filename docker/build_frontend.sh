#!/bin/bash
yarn install
yarn add cross-env
yarn run production
cp -rv ./public/favicon.ico ./public/index.php ./public/robots.txt ./public/web.config ./static/
cp -rv ./public/css/ ./public/fonts/ ./public/img/ ./public/js/ ./public/sounds/ ./public/mix-manifest.json ./static/