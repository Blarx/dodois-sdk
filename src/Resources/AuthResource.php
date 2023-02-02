<?php
namespace Dodois\Resources;

use Dodois\Concerns\Resource\CanAccessClient;
use Dodois\Contracts\ResourceContract;
use Dodois\Requests\Auth\RolesRequest;
use Dodois\Requests\Auth\UnitsRequest;

class AuthResource extends ResourceContract
{
    use CanAccessClient;

    public function units(): UnitsRequest
    {
        return new UnitsRequest($this);
    }

    public function roles(): RolesRequest
    {
        return new RolesRequest($this);
    }
}
