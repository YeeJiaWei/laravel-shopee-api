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
    private array $configs;
    private int $shopId;
    private mixed $accessToken;

    public function __construct(array $config)
    {
        $this->shopId = $config['shop_id'] ?? null;
        $this->accessToken = $config['access_token'] ?? null;
        $this->configs = config('services.shopee');
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
