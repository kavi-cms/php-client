## kavicms package

### Installation steps

1- Require from your main composer.json file.

2- Update your composer.json like below
```json
  {
    "require": {
        "kavicms/kavicms-laravel": "dev-development"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/kavi-cms/php-client"
        }
    ]
  }
```
3- Run `composer update` to load package.

4- Since this package has routes and, default package providers loads before your `RouteServiceProvider` it can override any of your routes within `routes/web.php`. To handle this problem this package will not automatically export its `KaviCmsRouteServiceProvider`. You should update your `providers` value inside `config/app.php` file. At the end, your `config/app.php` should look similar to below snippet. 

```php
    'providers' => ServiceProvider::defaultProviders()->merge([
        // your other providers ...
        App\Providers\RouteServiceProvider::class,

        Kavicms\KavicmsLaravel\KaviCmsRouteServiceProvider::class, // put this line after RouteServiceProvider to give it less priority 
    ])->toArray(),
```

5- Update your `.env` file like below

```php
KAVICMS_API_URL="" # optional
KAVICMS_AUTH_URL="" # optional
KAVICMS_CLIENT_ID="<your_client_id>"
KAVICMS_CLIENT_SECRET="<your_client_secret>"
```

