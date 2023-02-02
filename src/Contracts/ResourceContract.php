<?php
namespace Dodois\Contracts;


/**
 * @property-read \Dodois\Contracts\ClientContract $client
 */
interface ResourceContract
{
    public function client(): ClientContract;
}
