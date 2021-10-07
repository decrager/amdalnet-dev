## Getting started

### Prerequisites

 * Laravel
 * Php 
 * Node JS

### Installing
#### Manual

```bash
# Clone the project and run composer
composer create-project tuandm/laravue
cd laravue

# Migration and DB seeder (after changing your DB settings in .env)
php artisan migrate --seed

# Install dependency with NPM
npm install

# develop
npm run dev # or npm run watch

# Build on production
npm run production
```

#### Docker
```sh
docker-compose up -d

docker-compose exec laravel bash
$ composer install
$ npm install -g cross-env && npm install && npm run production
```
Build static files within Laravel container with npm
```sh
# Get laravel docker container ID from containers list
docker ps

docker exec -it <container ID> npm run dev # or npm run watch
docker exec -it laravue_laravel_1 npm run watch
# Where <container ID> is the "laravel" container name, ex: src_laravel_1
```
Open http://localhost:8000 (laravel container port declared in `docker-compose.yml`) to access Laravue

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

## Workflow Test

```
# list command
php artisan list

# create graphviz for workflow 
php artisan workflow:dump straight --class App\\Entity\\BlogPost

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
```

- master
  - lpjp
  - tenaga ahli
    - type: penyusun, lepas
    - lpjp, non lpjp (mandiri)
    - registration_number
    - certificate_number
    - ktpa - ketua
    - atpa
  - 
- tim penyusun
- kegiatan