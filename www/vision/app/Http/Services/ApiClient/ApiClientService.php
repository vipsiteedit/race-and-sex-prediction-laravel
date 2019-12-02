<?php


namespace App\Http\Services\ApiClient;

use App\Http\Controllers\Controller;

/**
 * Class ApiClientService
 * @package App\Http\Services\ApiClient
 */
class ApiClientService
{
    public static function get(ApiClientInterface $class)
    {
        return self::request('GET', $class);
    }

    public static function put(ApiClientInterface $class)
    {
        return self::request('PUT', $class);
    }

    public static function post(ApiClientInterface $class)
    {
        return self::request('POST', $class);
    }

    public static function delete(ApiClientInterface $class)
    {
        return self::request('DELETE', $class);
    }

    private static function request($method = 'GET', ApiClientInterface $class)
    {
        $client = new \GuzzleHttp\Client($class->headers());
        $response = $client->request( $method, $class->baseUrl() . $class->uri, $class->option());
        $class->status($response->getStatusCode());
        if ($response->getStatusCode()==200) {
           return $class->parseResult(strval($response->getBody()));
        } else {
            return [];
        }
    }
}
