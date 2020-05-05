#!/usr/bin/env sh

# abort on errors
set -e

# build
npm run build

# navigate into the build output directory
cd dist

git init
heroku git:remote -a seo-admin
git add -A
git push heroku master

cd -