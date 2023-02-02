<?php

namespace Dodois\Concerns\Request;

use Illuminate\Support\Carbon;

/**
 * @mixin \Dodois\Contracts\ResourceContract
 */
trait HasWhere
{
    /**
     * @var array <string, mixed>
     */
    protected $currentQuery = [];

    public function whereBetween(Carbon $from, Carbon $to): static
    {
        return $this->where('from', $from)->where('to', $to);
    }

    public function where(string $field, mixed $value = null): static
    {
        $this->currentQuery[$field] = $value;

        $options = $this->resource()->client()->request->getOptions();

        $query = data_get($options, 'query', []);

        $query[$field] = $this->prepareWhereValue($value);

        data_set($options, 'query', $query);

        $this->resource()->client()->request->withOptions($options);

        return $this;
    }

    protected function prepareWhereValue(mixed $value): mixed
    {
        return match (true) {
            $value instanceof Carbon => $value->toIso8601String(),
            is_string($value) => $value,
            is_array($value) => implode(',', $value),
            is_bool($value) => $value ? 'true' : 'false',
            is_null($value) => 'null',
            default => $value,
        };
    }

    protected function getWhereQuery(array $query = []): array
    {
        $query = count($query) ? $query : $this->currentQuery;

        foreach ($query as $key => $value) {
            $query[$key] = $this->prepareWhereValue($value);
        }

        return $query;
    }
}
