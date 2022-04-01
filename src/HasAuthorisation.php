<?php


namespace Yeejiawei\LaravelShopeeApi;

use GuzzleHttp\RequestOptions;

trait HasAuthorisation
{
    private function generateAuthSignature($uri): string
    {
        $base_string = $this->getConfigs()['partner_id'] . $uri . $this->getTimestamp();

        return hash_hmac('sha256', $base_string, $this->getConfigs()['partner_key']);
    }

    public static function getAuthorisationUrl(): string
    {
        $static = new static();
        $uri = '/api/v2/shop/auth_partner';

        return 'https://partner.test-stable.shopeemobile.com/' . $uri . '?' . http_build_query([
                'partner_id' => $static->getConfigs()['partner_id'],
                'redirect' => $static->getConfigs()['redirect'],
                'timestamp' => $static->getTimeStamp(),
                'sign' => $static->generateAuthSignature($uri),
            ]);
    }

    public static function retriveAccessTokenFromCode(int $shop_id, string $code)
    {
        $static = new static();
        $uri = '/api/v2/auth/token/get';


        $client = new \GuzzleHttp\Client(["base_uri" => $static->getConfigs()['host']]);

        $request = $client->request('POST', $uri, [
            RequestOptions::QUERY => [
                'partner_id' => $static->getConfigs()['partner_id'],
                'timestamp' => $static->getTimeStamp(),
                'sign' => $static->generateAuthSignature($uri),
            ],
            RequestOptions::JSON => [
                'code' => $code,
                'partner_id' => $static->getConfigs()['partner_id'],
                'shop_id' => $shop_id,
            ],
        ]);

        return $request->getBody()->getContents();
    }
}
