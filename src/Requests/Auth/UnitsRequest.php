<?php
namespace Dodois\Requests\Auth;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class UnitsRequest implements RequestContract
{
    use HasResource, HasValidation;

    public function list(): Collection
    {
        $response = $this->resource()->client()->send(
            Method::GET,
            'auth/roles/units',
        );

        throw_if(
            ! $response->ok(),
            $response->ok() ?: new DodoisClientException(
                $response->body() ?: $response->reason(),
                $response->status(),
            ),
        );

        // $data = $response->json();
        // $this->validateResponse($data, ['unitRoles'], 'auth/roles/units');

        return collect($response->json())->keyBy('id');
    }
}
