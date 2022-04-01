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
    public $shopId;

    public function __construct($shopId)
    {
        $this->shopId = $shopId;
        $this->configs = config('services.shopee');
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
