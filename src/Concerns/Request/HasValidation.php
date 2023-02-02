<?php
namespace Dodois\Concerns\Request;

use Dodois\Exceptions\DodoisClientException;
use Illuminate\Support\Carbon;

trait HasValidation
{
    /**
     * Validate params array when:
     * 'from' => \Illuminate\Support\Carbon
     * 'to' => \Illuminate\Support\Carbon
     * 'units' => array|string
     *
     *
     * @param  array  $params  Array of params
     * @return void
     *
     * @throws \Dodois\Exceptions\DodoisClientException
     */
    public function validateUnitParams(array $params): void
    {
        throw_if(
            ! isset($params['from']) ||
            ! isset($params['to']) ||
            ! isset($params['units']),
            DodoisClientException::class,
            'Parameters "from", "to" and "units" is required',
        );

        /**
         * @var \Illuminate\Support\Carbon $from
         * @var \Illuminate\Support\Carbon $to
         * @var string|array<int, string> $units
         */
        ['from' => $from, 'to' => $to, 'units' => $units] = $params;

        throw_if(
            ! $from instanceof Carbon ||
            ! $to instanceof Carbon,
            DodoisClientException::class,
            'Parameters "from", "to" is not Illuminate\Support\Carbon instance',
        );
        throw_if(
            $from->gt($to),
            DodoisClientException::class,
            'From should be less than To',
        );
        throw_if(
            $from->diff($to)->days > 31,
            DodoisClientException::class,
            'From-To Period is too long(>31 days)',
        );
        throw_if(
            ! is_array($units) && ! is_string($units),
            DodoisClientException::class,
            'Unexpected "units" value',
        );
    }

    /**
     * Validate response array
     *
     * @param  array  $json  Json Response
     * @param  array  $wantKeys  Keys in response
     * @param  string  $apiResource  Name of Api resource
     * @return void
     *
     * @throws \Dodois\Exceptions\DodoisClientException
     */
    public function validateResponse(array $json, array $wantKeys, string $apiResource): void
    {
        if (! count($wantKeys)) {
            return;
        }

        foreach ($wantKeys as $key) {
            if (! is_string($key)) {
                continue;
            }

            throw_if(
                ! isset($json[$key]),
                new DodoisClientException(sprintf(
                    'Unexpected response on "%s": key "%s" not found',
                    $apiResource, $key,
                )),
            );
        }
    }
}
