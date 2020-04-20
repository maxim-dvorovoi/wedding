#!/usr/bin/env sh

# abort on errors
set -e

# build
npm run build

# navigate into the build output directory
cd dist

git init
git add -A
git commit -m 'deploy'
git push -u https://github.com/maxim-dvorovoi/vue_site_template.git master:gh-pages

cd -