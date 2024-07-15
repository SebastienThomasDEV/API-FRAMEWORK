<?php

namespace Sthom\Back\Service;

class JwtManager implements ServiceInterface
{

    private readonly string $algo;

    private readonly string $key;


    public final function initialize(): void
    {
        $this->algo = $_ENV['JWT_ALGORITHM'];
        $this->key = $_ENV['JWT_SECRET_KEY'];
    }

    public final function encode(array $data): string
    {
        return $this->jwt_encode($data, $this->key, $this->algo);
    }
    public final function decode(string $token): mixed
    {
        return $this->jwt_decode($token);
    }

    private function jwt_encode(array $data, string $key, string $algo): string
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => $algo]);
        $payload = json_encode($data);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }


    private function jwt_decode(string $token): mixed
    {
        return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))), true);
    }


    public final function __construct()
    {
        $this->initialize();
    }


}