<?php
namespace Dodois\Requests\Production;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class StopSalesProductsRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $query = $this->getWhereQuery($query);

        $this->validateUnitParams($query);

        $response = $this->resource()->client()->send(
            Method::GET,
            'production/stop-sales-products',
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
            ['stopSalesByProducts'],
            'production/stop-sales-products',
        );

        return collect($data['stopSalesByProducts']);
    }
}
