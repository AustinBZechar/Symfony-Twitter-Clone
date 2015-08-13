<?php

namespace AppBundle\Redis;

use AppBundle\Exception\WrongUsernameOrPasswordException;
use Predis\Client;

class RedisLogin
{
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
     * @param $username
     * @param $password
     *
     * @throws WrongUsernameOrPasswordException
     *
     * @return string
     */
    public function login($username, $password)
    {
        $userId = $this->redisClient->get("username:$username:id");
        if (!$userId) {
            throw new WrongUsernameOrPasswordException(WrongUsernameOrPasswordException::MESSAGE);
        }
        $realPassword = $this->redisClient->get("uid:$userId:password");
        if ($realPassword != $password) {
            throw new WrongUsernameOrPasswordException(WrongUsernameOrPasswordException::MESSAGE);
        }

        return $this->redisClient->get("uid:$userId:auth");
    }
}
