<?php
namespace Dodois\Requests\Accounting;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class WriteOffsProductsRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $this->validateUnitParams($this->getWhereQuery($query, false));

        $query = $this->getWhereQuery($query);
        $skip = 0;
        $take = 1000;

        $writeOffs = [];

        do {
            $query['skip'] = $skip;
            $query['take'] = $take;

            $response = $this->resource()->client()->send(
                Method::GET,
                'accounting/write-offs/products',
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
                ['writeOffs', 'isEndOfListReached'],
                'accounting/write-offs/products',
            );

            $writeOffs = [...$writeOffs, ...$data['writeOffs']];
            $skip += $take;
        } while (! $data['isEndOfListReached']);

        return collect($writeOffs);
    }
}
