<?php
namespace Dodois\Requests\Accounting;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class IncomingStockItemsRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $this->validateUnitParams($this->getWhereQuery($query, false));

        $query = $this->getWhereQuery($query);
        $skip = 0;
        $take = 1000;

        $incomingStockItems = [];

        do {
            $query['skip'] = $skip;
            $query['take'] = $take;

            $response = $this->resource()->client()->send(
                Method::GET,
                'accounting/incoming-stock-items',
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
                ['incomingStockItems', 'isEndOfListReached'],
                'accounting/incoming-stock-items',
            );

            $incomingStockItems = [...$incomingStockItems, ...$data['incomingStockItems']];
            $skip += $take;
        } while (! $data['isEndOfListReached']);

        return collect($incomingStockItems);
    }
}
