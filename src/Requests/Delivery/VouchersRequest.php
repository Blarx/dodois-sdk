<?php
namespace Dodois\Requests\Delivery;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class VouchersRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function list(array $query = []): Collection
    {
        $query = $this->getWhereQuery($query);

        $this->validateUnitParams($query);

        $skip = 0;
        $take = 1000;

        $vouchers = [];

        do {
            $query['skip'] = $skip;
            $query['take'] = $take;

            $response = $this->resource()->client()->send(
                Method::GET,
                'delivery/vouchers',
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
                ['vouchers', 'isEndOfListReached'],
                'delivery/vouchers',
            );

            $vouchers = [...$vouchers, ...$data['vouchers']];
            $skip += $take;
        } while (! $data['isEndOfListReached']);

        return collect($vouchers);
    }
}
