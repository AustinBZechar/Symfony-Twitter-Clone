<?php

namespace AppBundle\Redis;

use AppBundle\Exception\UsernameExistsException;
use AppBundle\Utils\RandomizeTrait;
use Predis\Client;

class RedisRegistration
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
     * @param $username
     *
     * @return boolean
     */
    private function checkIfUsernameExists($username)
    {
        if ($this->redisClient->get("username:$username:id")) {
            return true;
        }

        return false;
    }

    /**
     * @param $username
     * @param $password
     *
     * @throws UsernameExistsException
     *
     * @return string
     */
    public function register($username, $password)
    {
        if ($this->checkIfUsernameExists($username)) {
            throw new UsernameExistsException(UsernameExistsException::MESSAGE);
        }
        $userId = $this->redisClient->incr("global:nextUserId");
        $this->redisClient->set("username:$username:id", $userId);
        $this->redisClient->set("uid:$userId:username", $username);
        $this->redisClient->set("uid:$userId:password", $password);

        $authSecret = $this->getRand();
        $this->redisClient->set("uid:$userId:auth", $authSecret);
        $this->redisClient->set("auth:$authSecret", $userId);

        // TODO fix this sadd, expects array, got int
//        $this->redisClient->sadd("global:users", $userId);

        return $authSecret;
    }
}
