<?php
namespace Dodois\Requests\Auth;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class RolesRequest implements RequestContract
{
    use HasResource, HasValidation;

    public function list(): Collection
    {
        $response = $this->resource()->client()->send(
            Method::GET,
            'auth/roles/list',
        );

        throw_if(
            ! $response->ok(),
            $response->ok() ?: new DodoisClientException(
                $response->body() ?: $response->reason(),
                $response->status(),
            ),
        );

        $data = $response->json();

        $this->validateResponse($data, ['roles'], 'auth/roles/list');

        return collect($data['roles'])->keyBy('id');
    }
}
