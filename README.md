## Dodois API SDK

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

How it use? In Controller:

```php
use Dodois\Contracts\ClientContract;
use Dodois\Contracts\PublicApiContract;

...

class PageController {
    public function page(ClientContract $dodois, ...) {
        
        ...
        // Query to auth/ resource
        $units = $dodois->withToken('access_token')
            ->auth()->units()->list();
        
        $roles = $dodois->withToken('access_token')
            ->auth()->roles()->list();
        
        // Prefix config
        $products = $dodois->withToken('access_token')
            ->accounting('dodopizza', 'ru') // Default
            ->products()
            ->where('isProducible', true)
            ->list();
        
        // Where Variant One
        $sales = $dodois->withToken('access_token')
            ->accounting()->sales()
            ->whereBetween(
                now()->subDay(), // From
                now(), // To
            )
            ->where('units', $units->pluck('id'))
            ->where('salesChannel', 'Delivery')
            ->list();
        
        // Where Variant Two
        $products = $dodois->withToken('access_token')
            ->accounting()->semiFinishedProductsProduction()
            ->list([
                'from' => now()->subDay(),
                'to' => now(),
                'units' => $units->pluck('id'),
            ]);
    }
    
    public function other(PublicApiContract $dodois, ...)
    {
        $units = $dodois->units();
        $unitsKz = $dodois->units('kz');
    }
}
```

## Installation

You can install the package via composer:

```bash
composer require blarx/dodois-sdk
```

The package will automatically register itself.

You can publish the config with:

```bash
php artisan vendor:publish --provider="Dodois\DodoisServiceProvider" --tag="dodois-config"
```

Add in you .env file:

```env
DODOIS_CLIENTID=
DODOIS_SECRET=
DODOIS_REDIRECTURL=http://localhost/dodois/callback
```
