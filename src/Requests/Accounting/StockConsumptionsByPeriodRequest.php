<?php
namespace Dodois\Requests\Accounting;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class StockConsumptionsByPeriodRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $this->validateUnitParams($this->getWhereQuery($query, false));

        $query = $this->getWhereQuery($query);
        $skip = 0;
        $take = 1000;

        $consumptions = [];

        do {
            $query['skip'] = $skip;
            $query['take'] = $take;

            $response = $this->resource()->client()->send(
                Method::GET,
                'accounting/stock-consumptions-by-period',
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
                ['consumptions', 'isEndOfListReached'],
                'accounting/stock-consumptions-by-period',
            );

            $consumptions = [...$consumptions, ...$data['consumptions']];
            $skip += $take;
        } while (! $data['isEndOfListReached']);

        return collect($consumptions);
    }
}
