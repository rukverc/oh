# OH

### Run it:

1. `$ composer install`
2. `$ php -S 0.0.0.0:8888 -t public public/index.php`
3. Browse to http://localhost:8888

## Key directories

* `app`: Application code
* `app/src`: All class files within the `App` namespace
* `app/templates`: Twig template files
* `cache/twig`: Twig's Autocreated cache files
* `log`: Log files
* `public`: Webserver root
* `vendor`: Composer dependencies

## Key files

* `public/index.php`: Entry point to application
* `app/settings.php`: Configuration
* `app/dependencies.php`: Services for Pimple
* `app/middleware.php`: Application middleware
* `app/routes.php`: All application routes are here
* `app/src/Action/HomeAction.php`: Action class for the home page
* `app/src/Action/CheckAction.php`: Action class for the calculation page
* `app/src/Action/CheckPostAction.php`: Action class for the calculation background post page
* `app/templates/home.twig`: Twig template file for the home page
* `app/templates/check.twig`: Twig template file for the calculation page

