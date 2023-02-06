<?php
namespace Dodois;

use Dodois\Contracts\ClientContract;
use Illuminate\Http\Client\PendingRequest;

class Client implements ClientContract
{
    use Concerns\Client\SendRequest;

    public function __construct(
        public readonly PendingRequest $request,
    ) {}

    public function auth(): Resources\AuthResource
    {
        return new Resources\AuthResource($this);
    }

    /**
     * @param  string  $name (dodopizza|doner42|drinkit)
     * @param  string  $lang (ru|...)
     */
    public function accounting(
        string $name = 'dodopizza',
        string $lang = 'ru',
    ): Resources\AccountingResource {
        return new Resources\AccountingResource($this->prepare(implode('?', [
            $name, $lang,
        ])));
    }

    /**
     * @param  string  $name (dodopizza|doner42|drinkit)
     * @param  string  $lang (ru|...)
     */
    public function delivery(
        string $name = 'dodopizza',
        string $lang = 'ru',
    ): Resources\DeliveryResource {
        return new Resources\DeliveryResource($this->prepare(implode('?', [
            $name, $lang,
        ])));
    }

    /**
     * @param  string  $name (dodopizza|doner42|drinkit)
     * @param  string  $lang (ru|...)
     */
    public function production(
        string $name = 'dodopizza',
        string $lang = 'ru',
    ): Resources\ProductionResource {
        return new Resources\ProductionResource($this->prepare(implode('?', [
            $name, $lang,
        ])));
    }

    /**
     * @param  string  $name (dodopizza|doner42|drinkit)
     * @param  string  $lang (ru|...)
     */
    public function staff(
        string $name = 'dodopizza',
        string $lang = 'ru',
    ): Resources\StaffResource {
        return new Resources\StaffResource($this->prepare(implode('?', [
            $name, $lang,
        ])));
    }
}
