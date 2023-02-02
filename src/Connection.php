<?php
namespace Dodois;

use Dodois\Contracts\ConnectionContract;
use Dodois\Events\Connected;
use Dodois\Exceptions\DodoisConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Connection implements ConnectionContract
{
    public const OPENID_AUTH = 'https://auth.dodois.io/.well-known/openid-configuration';

    function __construct(
        protected string $clientId,
        protected string $clientSecret,
        protected string $callbackUri,
    ) {}

    /**
     * Get OpenId Configuration from OAuth server
     */
    public function getOAuthConfiguration(): array
    {
        return Cache::remember(
            'dodois.oauth',
            now()->addDay(),
            [$this, 'makeOAuthConfigurationRequest'],
        );
    }

    /**
     * Request OpenId Configuration from OAuth servers
     *
     * @throws \Dodois\DodoisConnectionException
     */
    protected function makeOAuthConfigurationRequest()
    {
        $response = Http::get(static::OPENID_AUTH);

        throw_if(
            !$response->ok(),
            $response->ok() ?: new DodoisConnectionException(sprintf(
                'Unexpected OAuth OpenId Configuration response: %s',
                $response->body() ?: $response->reason(),
            ), $response->status()),
        );

        return $response->json();
    }

    /**
     * Generate User OAuth Authorization link
     *
     * @throws \Dodois\DodoisConnectionException
     */
    public function generateAuthLink(string $codeVerifier): string
    {
        $scope = config('dodois.scope');
        $configration = $this->getOAuthConfiguration();
        $codeChallenge = strtr(rtrim(
            base64_encode(hash('sha256', $codeVerifier, true)), '=',
        ), '+/', '-_');

        return implode('?', [
            $configration['authorization_endpoint'],
            http_build_query([
                'client_id' => $this->clientId,
                'scope' => $scope,
                'response_type' => 'code',
                'redirect_uri' => $this->callbackUri,
                'code_challenge' => $codeChallenge,
                'code_challenge_method' => 'S256',
            ]),
        ]);
    }

    /**
     * Make Token Request to /token endpoint
     *
     * @throws \Dodois\DodoisConnectionException
     */
    public function makeTokenRequest(string $codeVerifier, string $code): array
    {
        $scope = config('dodois.scope');
        $configration = $this->getOAuthConfiguration();

        $response = Http::asForm()->post($configration['token_endpoint'], [
            'client_id' => $this->clientId,
            'code_verifier' => $codeVerifier,
            'scope' => $scope,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->callbackUri,
            'client_secret' => $this->clientSecret,
        ]);

        throw_if(
            !$response->ok(),
            $response->ok() ?: new DodoisConnectionException(sprintf(
                'Unexpected Token response on "token_endpoint": %s',
                $response->body() ?: $response->reason(),
            ), $response->status()),
        );

        $data = $response->json();

        Connected::dispatch($data);

        return $data;
    }

    /**
     * Make Refresh Token Request to /token endpoint
     *
     * @throws \Dodois\DodoisConnectionException
     */
    public function makeRefreshTokenRequest(string $refreshToken): array
    {
        $configration = $this->getOAuthConfiguration();

        $response = Http::asForm()->post($configration['token_endpoint'], [
            'grant_type' => 'refresh_token',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ]);

        throw_if(
            !$response->ok(),
            $response->ok() ?: new DodoisConnectionException(sprintf(
                'Unexpected RefreshToken response on "token_endpoint": %s',
                $response->body() ?: $response->reason(),
            ), $response->status()),
        );

        $data = $response->json();

        Connected::dispatch($data, true);

        return $data;
    }
}
