<?php
namespace Dodois\Contracts;

use Dodois\Enums\Method;
use Illuminate\Http\Client\Response;

/**
 * @property-read \Illuminate\Http\Client\PendingRequest $request
 */
interface ClientContract
{
    public function send(Method $method, string $url, array $options = []): Response;
}
