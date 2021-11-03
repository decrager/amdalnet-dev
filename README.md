<p align="center">
  <img width="320" src="http://153.92.4.138/amdal_info/img/logo.png">
</p>
<p align="center">
  <a href="https://laravel.com">
    <img src="https://img.shields.io/badge/laravel-7.3-brightgreen.svg" alt="vue">
  </a>
  <a href="https://github.com/vuejs/vue">
    <img src="https://img.shields.io/badge/vue-2.6.10-brightgreen.svg" alt="vue">
  </a>
  <a href="https://github.com/ElemeFE/element">
    <img src="https://img.shields.io/badge/element--ui-2.13.0-brightgreen.svg" alt="element-ui">
  </a>
</p>


## Getting started

### Requirement

### Installing

Create .env file by

copy `.env.example` to `.env`
or
copy `docker/docker.env` to `.env`
#### Manual

```bash
# Migration and DB seeder (after changing your DB settings in .env)
php artisan migrate --seed

# Install dependency with NPM
npm install

# develop
npm run dev # or npm run watch

# Build on production
npm run production
```


docker-compose exec laravel bash
$ composer install
$ npm install -g cross-env && npm install && npm run production
```
Build static files within Laravel container with npm
```sh
# Get laravel docker container ID from containers list
docker ps

docker exec -it <container ID> npm run dev # or npm run watch
docker exec -it amdalnet_laravel_1 npm run watch

# Where <container ID> is the "laravel" container name, ex: src_laravel_1
```
Open http://localhost:8000 (laravel container port declared in `docker-compose.yml`) to access Amdalnet

## Running the tests
* Tests system is under development

## Deployment and/or CI/CD
check:
- Envoy.blade.php [Envoy](https://laravel.com/docs/5.8/envoy)
- .gitlab-ci.yml [GitLab CI/CD](https://about.gitlab.com/product/continuous-integration/)
## Built with
* [Laravel](https://laravel.com/) - The PHP Framework For Web Artisans
* [Laravel Sanctum](https://github.com/laravel/sanctum/) - Laravel Sanctum provides a featherweight authentication system for SPAs and simple APIs.
* [spatie/laravel-permission](https://github.com/spatie/laravel-permission) - Associate users with permissions and roles.
* [VueJS](https://vuejs.org/) - The Progressive JavaScript Framework
* [Element](https://element.eleme.io/) - A  Vue 2.0 based component library for developers, designers and product managers
* [Vue Admin Template](https://github.com/PanJiaChen/vue-admin-template) - A minimal vue admin template with Element UI


## Acknowledgements

* [vue-element-admin](https://panjiachen.github.io/vue-element-admin/#/) 
* [tui.editor](https://github.com/nhnent/tui.editor) - Markdown WYSIWYG Editor.
* [Echarts](http://echarts.apache.org/) - A powerful, interactive charting and visualization library for browser.


## Workflow Test

```
# list command
php artisan list

# create graphviz for workflow 
php artisan workflow:dump straight --class App\\Entity\\BlogPost
php artisan workflow:dump amdalnet --class App\\Entity\\Project
php artisan workflow:dump amdal --class App\\Entity\\Project

# create blogpost model
php artisan make:model 'Entity\BlogPost' --migration
php artisan make:factory BlogPostFactory --model='Entity\BlogPost'
php artisan migrate --seed
php artisan db:wipe
php artisan db:seed --class=BlogPostTableSeeder

# test workflow
php artisan workflow:test

# create listener
php artisan make:listener BlogPostWorkflowSubscriber

# docker shell
docker-compose exec laravel bash
```

# Php installation
```
sudo apt-get install -y graphviz libmcrypt-dev libjpeg-dev libpng-dev libfreetype6-dev libbz2-dev libonig-dev libzip-dev libpq-dev
sudo apt-get install php-mbstring php-xml php-pgsql php-gd php-zip 
```

```
sudo php /var/www/amdal/artisan octane:start --server=swoole --port=8000 
```