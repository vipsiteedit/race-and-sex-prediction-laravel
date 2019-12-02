<?php


namespace App\Http\oVision;


use App\Http\Services\ApiClient\ApiClientInterface;

abstract class ApiClient implements ApiClientInterface
{
    public $uri = '/';
    private $result;

    public function baseUrl(): string
    {
        // TODO: Implement baseUrl() method.
        return 'http://race-and-sex-prediction-dev.us-east-2.elasticbeanstalk.com';
    }

    public function headers(): array
    {
        return [];
    }

    public function status(int $statusCode)
    {
        return $statusCode;
    }

    public function option(): array
    {
        return [];
    }

    public function parseResult(string $body): array
    {
        if (!empty($body)) {
            return json_decode($body, true);
        } else {
            return [];
        }
    }

    protected function apiException($message)
    {
        return  $message;
    }
}
