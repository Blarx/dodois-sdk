<?php
namespace Dodois\Concerns\Resource;

use Dodois\Contracts\ClientContract;

/**
 * @mixin ResourceContract
 */
trait CanAccessClient
{
    public function __construct(
        private readonly ClientContract $client,
    ) {}

    public function client(): ClientContract
    {
        return $this->client;
    }
}
