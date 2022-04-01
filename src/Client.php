<?php


namespace Yeejiawei\LaravelShopeeApi;


use BadMethodCallException;

/**
 * Class Client
 * @package Yeejiawei\LaravelShopeeApi
 *
 * @method \Yeejiawei\LaravelShopeeApi\NodeTwo\Chat chat()
 */
class Client
{
    use HasAuthorisation;

    private array $configs;
    private mixed $shopId;
    private mixed $accessToken;
    private int $timestamp;

    public function __construct(array $config = [])
    {
        $this->shopId = $config['shop_id'] ?? null;
        $this->accessToken = $config['access_token'] ?? null;
        $this->configs = config('services.shopee');
        $this->timestamp = now()->timestamp;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getConfigs()
    {
        return $this->configs;
    }

    public function getShopId()
    {
        return $this->shopId;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function generateSignature($uri): string
    {
        $baseString = $this->getConfigs()['partner_id'] . $uri . $this->getTimestamp() . $this->getAccessToken() . $this->getShopId();

        return hash_hmac('sha256', $baseString, $this->getConfigs()['partner_key']);
    }

    public function __call(string $name, array $arguments)
    {
        $className = 'Yeejiawei\\LaravelShopeeApi\\NodeTwo\\' . ucfirst($name);
        if (!class_exists($className)) {
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $className
            ));
        }

        return new $className($this);
    }
}
