<?php
namespace Dodois\Concerns\Request;

use Dodois\Contracts\ResourceContract;

/**
 * @mixin \App\Services\Dodois\Contracts\RequestContract
 */
trait HasResource
{
    public function __construct(
        private ResourceContract $resource,
    ) {
    }

    public function resource(): ResourceContract
    {
        return $this->resource;
    }
}
