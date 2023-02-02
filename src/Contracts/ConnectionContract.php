<?php
namespace Dodois\Contracts;


interface ConnectionContract
{
    function __construct(
        string $clientId,
        string $clientSecret,
        string $callbackUri,
    );

    public function generateAuthLink(string $codeVerifier): string;

    public function makeTokenRequest(string $codeVerifier, string $code): array;

    public function makeRefreshTokenRequest(string $refreshToken): array;
}
