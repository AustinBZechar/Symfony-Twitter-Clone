<?php

namespace AppBundle\Redis;

use AppBundle\Utils\RandomizeTrait;
use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class RedisLogout
{
    use RandomizeTrait;

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

    public function logout()
    {
        $userId = $this->session->get('userId');
        $newAuthSecret = $this->getRand();
        $oldAuthSecret = $this->redisClient->get("uid:$userId:auth");

        $this->redisClient->set("uid:$userId:auth", $newAuthSecret);
        $this->redisClient->set("auth:$newAuthSecret", $userId);
        $this->redisClient->del("auth:$oldAuthSecret");

        $this->session->set('userId', null);
        $this->session->set('username', null);
    }
}
