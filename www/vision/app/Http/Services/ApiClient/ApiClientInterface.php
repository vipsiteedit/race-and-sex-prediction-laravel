<?php


namespace App\Http\Services\ApiClient;


interface ApiClientInterface
{
    public function baseUrl(): string;

    public function headers(): array;

    public function option(): array;

    public function status(int $statusCode);

    public function parseResult(string $body): array;
}
