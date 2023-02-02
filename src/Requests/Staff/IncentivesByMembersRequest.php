<?php
namespace Dodois\Requests\Staff;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class IncentivesByMembersRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $query = $this->getWhereQuery($query);

        $this->validateUnitParams($query);

        $response = $this->resource()->client()->send(
            Method::GET,
            'staff/incentives-by-members',
            ['query' => $query],
        );

        throw_if(
            ! $response->ok(),
            $response->ok() ?: new DodoisClientException(
                $response->body() ?: $response->reason(),
                $response->status(),
            ),
        );

        $data = $response->json();

        $this->validateResponse(
            $data,
            ['staffMembers'],
            'staff/incentives-by-members',
        );

        return collect($data['staffMembers']);
    }
}
