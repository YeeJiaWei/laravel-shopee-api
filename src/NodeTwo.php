<?php


namespace Yeejiawei\LaravelShopeeApi;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\Utils;

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
        $this->timestamp = now()->timestamp;
    }

    private function getTimestamp(): int
    {
        return $this->timestamp;
    }

    private function generateSignature($uri)
    {

        $baseString = $this->client->getConfigs()['partner_id'] . $uri . $this->getTimestamp() . $this->client->getAccessToken() . $this->client->getShopId();
        // $baseString = $this->getPartnerId() . $uri . $this->getTimestamp() . $this->getAccessToken() . $this->getShopId();
        // dd($baseString);

        return hash_hmac('sha256', $baseString, $this->client->getConfigs()['partner_key']);
    }

    private function getDefaultParameters($uri)
    {
        return [
            'partner_id' => (int)$this->client->getConfigs()['partner_id'],
            'timestamp' => $this->getTimestamp(),
            'access_token' => $this->client->getAccessToken(),
            'shop_id' => (int)$this->client->getShopId(),
            'sign' => $this->generateSignature($uri),
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
                'query' => array_merge($this->getDefaultParameters($this->getUri($uri)), $parameters),
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
//            $client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);
//
//            $response = $client->request('POST', $this->getUri($uri), [
//                RequestOptions::QUERY => [$parameters],
//                RequestOptions::JSON => $this->getDefaultParameters($this->getUri($uri)),
//            ]);


            $baseUrl = new Uri($this->baseUrl);

            $uri = Utils::uriFor($this->getUri($uri));
            $path = $this->baseUrl . $uri->getPath();

            $uri = $uri
                ->withScheme($baseUrl->getScheme())
//                ->withUserInfo($this->baseUrl->getUserInfo())
                ->withHost($baseUrl->getHost())
//                ->withPort($this->baseUrl->getPort())
                ->withQuery(http_build_query($this->getDefaultParameters($this->getUri($uri))))
                ->withPath($uri->getPath());

//        $headers['Authorization'] = $this->signature($uri, $jsonBody);
//            $headers['User-Agent'] = $this->userAgent;
            $headers['Content-Type'] = 'application/json';

            $request = new Request(
                'POST', // All APIs should use POST method
                $uri,
                $headers,
                json_encode($parameters)
            );
//            dd($request);

            $http = new HttpClient();

            $response = $http->send($request);
            dd($response->getBody()->getContents());


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
