# Klever Skeleton

#### Slim 3 starter app  
Just the basics to get any project of the ground pretty quickly.

##### Includes:  
- Slim 3 - doh!  
- DI Container (PHP League)
- Basic DB driven Authentication
- Redis/Native Sessions
- Redis/Array Cache
- ORM (Laravel Eloquent) - Multi DB Connection support
- Basic Config Service
- Console Commands & Service Providers incl. Auto-Wiring Support

## Requirements
- PHP 7.0+

## Getting Started

Simply run

```bash
composer create-project madsem/klever-skeleton path/to/your/project
```

Create a database & 'users' table with at least the following fields:
- id
- username
- email
- password (must be hashed by php's password_hash() method)
- created_at
- updated_at

Rename ```.env.example``` to ```.env``` and fill in your environment details.

Then create asset directories in whatever structure you prefer, inside /public.

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

Global helpers (```bootstrap/helpers.php```) are available everywhere, even in the application config.

## Deploying

Run the following commands during deployment:
```bash
composer update

# production cache for config files
php klever cache:config
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
  
        return view('home.twig', [
            'posts' => $data,
        ]);
    }
}
```

CSRF Field in Views:
```html
{{ csrf.field | raw }}
```

Asset helper in views:
```html
{{ asset('css/app.css') }}

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
return redirect(route('auth.login'), 301)

# redirect to a third party
return redirect('http://google.com/, 302)

# get config items
config()->get('app.settings.db');

# retrieve something from global container
container()->get('myContainerItem');

# directly access app instance methods
app()->add('something');

```

## Built With

* [Slim Framework](http://slimframework.com) - Framework
* [Eloquent](https://github.com/illuminate/database) - ORM
* [PHP League](https://github.com/thephpleague/container) - Container

## Contributing

Feel free to send pull requests for bugs or minor enhancements only.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/madsem/slim-skeleton/tags). 

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## Acknowledgments

* Hat tip to Alex Garret @[CodeCourse.com](http://codecourse.com) for inspiration

