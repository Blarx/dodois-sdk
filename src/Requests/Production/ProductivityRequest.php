<?php
namespace Dodois\Requests\Production;

use Dodois\Concerns\Request\HasResource;
use Dodois\Concerns\Request\HasValidation;
use Dodois\Concerns\Request\HasWhere;
use Dodois\Contracts\RequestContract;
use Dodois\Enums\Method;
use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Collection;

class ProductivityRequest implements RequestContract
{
    use HasResource, HasValidation, HasWhere;

    public function get(array $query = []): Collection
    {
        $this->validateUnitParams($this->getWhereQuery($query, false));

        $query = $this->getWhereQuery($query);
        $response = $this->resource()->client()->send(
            Method::GET,
            'production/productivity',
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
            ['productivityStatistics'],
            'production/productivity',
        );

        return collect($data['productivityStatistics']);
    }

    public function periodic(int $period = 60, array $query = []): Collection
    {
        $query = $this->getWhereQuery($query, false);

        $this->validateUnitParams($query);

        /**
         * @var \Illuminate\Support\Carbon $from
         * @var \Illuminate\Support\Carbon $to
         * @var string|array<int, string> $units
         */
        ['from' => $from, 'to' => $to, 'units' => $units] = $query;

        $process = $from->clone()->startOfHour();
        $statistic = [];

        do {
            $start = $process->clone();
            $end = $start->clone()->addMinutes($period);

            $periodData = $this->get([
                'from' => $start,
                'to' => $end,
                'units' => $units,
            ]);

            $statistic = [...$statistic, ...$periodData->map(fn ($data) => [
                'startAt' => $start,
                'endAt' => $end,
                ...$data,
            ])->filter(fn ($data) => count($data) > 0)->all()];

            $process->addMinutes($period);
        } while ($process->lt($to));

        return collect($statistic);
    }
}
