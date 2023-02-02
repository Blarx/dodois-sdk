<?php

namespace Dodois\Requests\Accounting;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;


class ProductsRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $query = $this->getWhereQuery($query);

        $skip = 0;
        $take = 1000;

        $products = [];

        do {
            $query['skip'] = $skip;
            $query['take'] = $take;

            $response = $this->resource()->client()->send(
                Method::GET,
                'accounting/products',
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
                ['products', 'takenCount', 'totalCount'],
                'accounting/products',
            );

            $products = [...$products, ...$data['products']];
            $skip += $take;
        } while ($skip < $data['totalCount']);

        return collect($products);
    }
}
