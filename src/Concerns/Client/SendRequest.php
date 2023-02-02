<?php

namespace Dodois\Concerns\Client;

use Dodois\Enums\Method;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\RateLimiter;

/**
 * @property \Illuminate\Http\Client\PendingRequest $request
 */
trait SendRequest
{
    /**
     * @var string Requests UserAgent
     */
    protected string $userAgent = 'Dodois-SDK v1.0';

    /**
     * @var string Base API url
     */
    protected string $apiUrl = 'https://api.dodois.io';

    /**
     * @var int Rate limit requests count
     */
    protected int $perMinute = 40;

    /**
     * @var int Request retry when error times
     */
    protected int $retryWhenFailed = 3;

    /**
     * @var string Dodois Api token
     */
    protected string $token;

    /**
     * @param  \App\Services\Dodois\Method  $method  Enum HTTP Method
     * @param  string  $url  Method url
     * @param  array  $options  Request options
     * @return Illuminate\Http\Client\Response
     */
    public function send(Method $method, string $url, array $options = []): Response
    {
        // $channel = 'dodois-api-query:'.$this->token;

        // if (RateLimiter::tooManyAttempts($channel, $this->perMinute)) {
        //     $seconds = RateLimiter::availableIn($channel);

        //     sleep($seconds+1);
        // }

        $response = $this->request->throw()
            ->retry($this->retryWhenFailed, 100, function ($exception) {
                if (
                    $exception instanceof RequestException
                    && $exception->response->status() === 429
                    && ($json = $exception->response->json())
                    && isset($json['message'])
                    && preg_match('/Try again in ([0-9]+) seconds\./', $json['message'], $matches)
                ) {
                    sleep($matches[1] + 1);

                    return true;
                }
                if ($exception instanceof HttpClientException) {
                    return true;
                }

                return false;
            })
            ->withToken($this->token)
            ->withUserAgent($this->userAgent)
            ->timeout(120)->send(
                $method->value,
                $url,
                $options,
            );

        // RateLimiter::hit($channel);

        return $response;
    }

    /**
     * @param  string  $token Access Token
     * @return static
     */
    public function withToken(string $token): static
    {
        return tap($this, function () use ($token) {
            $this->token = $token;
        });
    }

    /**
     * @param  string  $prefix  Api prefix (dodopizza/ru)
     * @return static
     */
    protected function prepare(string $prefix = ''): static
    {
        return tap($this, function () use ($prefix) {
            $this->request->baseUrl(implode('/', [
                $this->apiUrl,
                $prefix,
            ]));
        });
    }
}
