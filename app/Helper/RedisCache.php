<?php

namespace App\Helper;

use Predis\Client;

class RedisCache
{
    public function __construct(int $dbIndex)
    {
        $this->cache = new Client([
            'scheme' => 'tcp',
            'host'   => config('database.redis.cache.host'),
            'port'   => config('database.redis.cache.port'),
            'database'  => $dbIndex
        ]);
        $this->cache->select($dbIndex);
    }

    public function setMessageByKeyWithTtl(string $key, string $message, $ttl = 3600): void
	{
        $this->cache->setex($key, $ttl, $message);
    }

	public function getByKey(string $key): array
	{
		return json_decode($this->cache->get($key), true);
	}
}

