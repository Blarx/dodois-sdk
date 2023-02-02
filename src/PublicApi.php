<?php
namespace Dodois;

use Dodois\Contracts\PublicApiContract;
use Dodois\Exceptions\DodoisPublicApiException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class PublicApi implements PublicApiContract
{
    /**
     * @var string Requests UserAgent
     */
    protected string $userAgent = 'Dodois-SDK v1.0';

    /**
     * @var string Base API url
     */
    protected string $apiUrl = 'https://publicapi.dodois.io';


    public function units(string $lang = 'ru'): Collection
    {
        $response = Http::asJson()->acceptJson()
            ->withUserAgent($this->userAgent)
            ->get(implode('/', [
                $this->apiUrl, $lang, 'api/v1/unitinfo/all',
            ]));

        throw_if(
            ! $response->ok(),
            $response->ok() ?: new DodoisPublicApiException(
                $response->body() ?: $response->reason(),
                $response->status(),
            ),
        );

        return collect($response->json());
    }
}
