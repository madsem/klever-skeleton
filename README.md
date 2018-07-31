# Klever Skeleton

#### Slim 3 starter app  
Just the basics to get any project of the ground pretty quickly.

##### Includes:  
- Slim 3 - doh!  
    * Slim Environment aware of X_HTTP_FORWARDED_PROTO to work behind SSL terminated AWS ELB's  
      (should be disabled if not using AWS ELB, in AppServiceProvider & ForceSslMiddleware)
- DI Container (PHP League)
- Basic DB driven Authentication
    * configurable session lifetimes for logged in / guests
- Redis/Native Sessions
    * Native should be used only for development
- Redis/Array Cache
    * Array should be used only for development
- Cookies
- ORM (Laravel Eloquent)
    * Multi DB Connection support
    * Pagination
- PHINX Migrations
    * Laravel Schema Builder integration
- Basic Config Service
- Symfony Console
- Auto-Wiring Support
- Flash Messages - Slim\Flash\Messages
- CSRF Protection - Slim\Csrf
    * Ajax Twig integration
- Valitron Validation

This skeleton deliberately has no asset management like webpack or gulp, nor specific asset directories / asset helper.  
So with each build it can be integrated as needed, for example build in a modular way for multi-domain / theme websites.

## Requirements
- PHP 7.2.0+
- Redis 4.0+ (For UNLINK support)

## Getting Started

Simply run

```bash
composer create-project madsem/klever-skeleton path/to/your/project
```

Rename ```.env.example``` to ```.env``` and fill in your environment details.  
Run the user migration.   
```vendor/bin/phinx migrate```  
(You should phinx to your PATH, then you can just use ```phinx migrate```)

You should be good to go now :)

## Gotcha's
This Slim 3 starter skeleton follows a Laravel kind of configuration model:  
Only use the ```env() ``` helper in config files.
To increase performance the production config files will be cached as an immutable array in ```cache/config/app.php```
and environment variables will not be read from again.

You can create new config files in ```config/``` and they will be loaded/cached automatically.
All config files need to return an array.

Service Providers, Console Commands are registered in ```config/app.php``` and are then registered in the container automatically.

Console commands, Controllers/Actions also support auto-wiring if required.

Global helpers (```bootstrap/helpers.php```) for pretty much anything, are available everywhere, even in the application config.

## Deploying

Run the following commands during deployment:
```bash
################
# On Prod Server
###############
# install composer dependencies
composer install -o

# production cache for config files
php klever cache:config

# migrate database
phinx migrate

```

## Console Commands

Clear all caches:
```bash
php klever cache:clear
```

## Examples

Example config for multiple DB connections:
```php
// ORM config
'connections'                       => [
    'default' => [
        'driver'    => env('DB_DRIVER', 'mysql'),
        'host'      => env('DB_HOST', 'localhost'),
        'database'  => env('DB_NAME', 'klever'),
        'username'  => env('DB_USER', 'user'),
        'password'  => env('DB_PASS', 'pass'),
        'charset'   => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix'    => env('DB_PREFIX', ''),
    ],
    'test' => [
        'driver'    => env('DB_DRIVER', 'mysql'),
        'host'      => env('DB_HOST', 'localhost'),
        'database'  => env('DB_NAME_TEST', 'klever'),
        'username'  => env('DB_USER', 'user'),
        'password'  => env('DB_PASS', 'pass'),
        'charset'   => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix'    => env('DB_PREFIX', ''),
    ],
],
```

To use another DB connection, simply set the ```$connection``` property on any model that uses this connection, like so:  
```php
protected $connection = 'test';
```

Example Controller
```php
namespace Klever\Controllers;  
  
use Klever\Models\Post;  
  
class HomeController
{

    function index()
    {

        session()->set('greeting', 'Hello Magnificient World!');
        
        session()->forget('greeting');
        
        session()->set('greeting', 'Goodbye Cruel World!');
  
        $data = cache()->remember(request()->getUri()->getPath() . '.homepage', 10, function () {
            return Post::all();
        });
  
        return view('home.twig', compact('data'));
    }
}
```

CSRF Field in Views:
```html
{{ csrf.field | raw }}
```

CSRF Field in Javascript:
```html
{{ csrf.object | raw }}
```

Various:
```php

# flash message
message('error', 'Ooops, something went wrong');

# read cookie
cookie()->get('cart');

# set cookie with 60 minute ttl
cookie()->set('cart', 'abandoned', 60);

# configure cookie settings before setting one
cookie()->config('http_only', false');

# redirect to named route
return redirect(route('auth.login'), 301);

# redirect to a third party
return redirect('http://google.com/', 302);

# get config items
config()->get('db');

# retrieve something from global container
container()->get('myContainerItem');

# directly access app instance methods
app()->add('something');

# paginate eloquent collection
{{ collectionName.links | raw }}

```

## Built With

* [Slim Framework](http://slimframework.com) - Framework
* [Eloquent](https://github.com/illuminate/database) - ORM
* [PHP League](https://github.com/thephpleague/container) - Container
* [Symfony Console](https://symfony.com/doc/current/components/console.html) - Console

## Contributing

Feel free to send pull requests for bugs or minor enhancements only.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/madsem/slim-skeleton/tags). 

## License

This project is licensed under the MIT License - see the [LICENSE.md](https://github.com/madsem/klever-skeleton/blob/master/LICENSE) file for details

## Acknowledgments

* Hat tip to Alex Garret @[CodeCourse.com](http://codecourse.com) for inspiration

