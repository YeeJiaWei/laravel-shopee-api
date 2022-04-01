<?php


namespace Yeejiawei\LaravelShopeeApi;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\RequestOptions;

class NodeTwo
{
    private $baseUrl = 'https://partner.test-stable.shopeemobile.com';

    private $apiPath = '/api/v2';

    protected $client;

    private $http;

    private $timestamp;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function getDefaultParameters($uri)
    {
        return [
            'partner_id' => (int)$this->client->getConfigs()['partner_id'],
            'timestamp' => $this->client->getTimestamp(),
            'access_token' => $this->client->getAccessToken(),
            'shop_id' => (int)$this->client->getShopId(),
            'sign' => $this->client->generateSignature($uri),
        ];
    }

    private function getUri($uri)
    {
        return $this->apiPath . $this->getNodePrefix() . $uri;
    }

    protected function get($uri, array $parameters = [])
    {
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

            $response = $client->request('GET', $this->getUri($uri), [
                RequestOptions::QUERY => array_merge($this->getDefaultParameters($this->getUri($uri)), $parameters),
            ]);
        } catch (ClientException $exception) {
            throw new \Exception($exception->getResponse()->getBody()->getContents());

        } catch (ServerException $exception) {
            throw new \Exception($exception->getResponse()->getBody()->getContents());
        }

        $contents = json_decode($response->getBody()->getContents());

        if (isset($contents->error) && $contents->error) {
            if (isset($contents->message)) {
                throw new \Exception('Shopee API Error: ' . $contents->message);
            } elseif (isset($contents->msg)) {
                throw new \Exception('Shopee API Error: ' . $contents->msg);
            }
            throw new \Exception('Unknown Shopee API Error');
        }

        return $contents->response;
    }

    protected function post($uri, array $parameters = [])
    {
        try {
            $client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

            $response = $client->request('POST', $this->getUri($uri), [
                RequestOptions::QUERY => $this->getDefaultParameters($this->getUri($uri)),
                RequestOptions::JSON => $parameters,
            ]);

        } catch (ClientException $exception) {
            throw new \Exception($exception->getResponse()->getBody()->getContents());

        } catch (ServerException $exception) {
            throw new \Exception($exception->getResponse()->getBody()->getContents());
        }

        $contents = json_decode($response->getBody()->getContents());

        if (isset($contents->error) && $contents->error) {
            if (isset($contents->message)) {
                throw new \Exception('Shopee API Error: ' . $contents->message);
            } elseif (isset($contents->msg)) {
                throw new \Exception('Shopee API Error: ' . $contents->msg);
            }
            throw new \Exception('Unknown Shopee API Error');
        }

        return $contents->response;
    }
}
