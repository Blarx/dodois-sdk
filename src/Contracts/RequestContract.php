<?php
namespace Dodois\Contracts;

/**
 * @property-read ResourceContract $resource
 */
interface RequestContract
{
    public function resource(): ResourceContract;
}
