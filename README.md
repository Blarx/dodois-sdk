## Dodois API SDK

```env
DODOIS_CLIENTID=
DODOIS_SECRET=
DODOIS_REDIRECTURI=/dodois/callback
```

In `EventServiceProvider`:
```php
use Dodois\Events\Connected;
...
protected $listen = [
    Connected::class => [
        YourTokenListener::class,
    ],
];
```

In Controller:
```php
use Dodois\Contracts\ClientContract;

...

class PageController {
    public function page(ClientContract $dodois, ...) {
        
        ...
        
        $units = $dodois->withToken('access_token')
            ->auth()->units()->list();
        
        dd($units);
    }
}
```

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-activitylog
```

The package will automatically register itself.

You can publish the config with:

```bash
php artisan vendor:publish --provider="Dodois\DodoisServiceProvider" --tag="dodois-config"
```
