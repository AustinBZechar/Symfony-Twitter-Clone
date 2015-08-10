<?php

namespace AppBundle\Redis;

use AppBundle\Exception\UsernameExistsException;
use Predis\Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RedisRegistration
{
    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param Client $redisClient
     * @param TokenStorage $tokenStorage
     * @param RequestStack $requestStack
     */
    public function __construct(Client $redisClient, TokenStorage $tokenStorage, RequestStack $requestStack)
    {
        $this->redisClient = $redisClient;
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
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
            throw new UsernameExistsException('Username exists');
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

    /**
     * @return string
     */
    private function getRand()
    {
        $fd = fopen("/dev/urandom", "r");
        $data = fread($fd, 16);
        fclose($fd);
        return md5($data);
    }
}
