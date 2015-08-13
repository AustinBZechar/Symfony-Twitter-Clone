<?php

namespace AppBundle\Redis;

use AppBundle\Utils\RandomizeTrait;
use Predis\Client;

class RedisLogout
{
    use RandomizeTrait;

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param $userId
     */
    public function logout($userId)
    {
        $newAuthSecret = $this->getRand();
        $oldAuthSecret = $this->redisClient->get("uid:$userId:auth");

        $this->redisClient->set("uid:$userId:auth", $newAuthSecret);
        $this->redisClient->set("auth:$newAuthSecret", $userId);
        $this->redisClient->del("auth:$oldAuthSecret");
    }
}
