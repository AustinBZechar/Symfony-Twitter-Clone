<?php

namespace AppBundle\Redis;

use AppBundle\Exception\WrongUsernameOrPasswordException;
use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class RedisLogin
{
    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param Client $redisClient
     * @param Session $session
     */
    public function __construct(Client $redisClient, Session $session)
    {
        $this->redisClient = $redisClient;
        $this->session = $session;
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

        $this->session->set('userId', $userId);
        $this->session->set('username', $username);

        return $this->redisClient->get("uid:$userId:auth");
    }
}
