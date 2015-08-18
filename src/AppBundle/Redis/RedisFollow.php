<?php

namespace AppBundle\Redis;

use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class RedisFollow
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
     * @var string
     */
    private $sessionUserId;

    /**
     * @param Client $redisClient
     * @param Session $session
     */
    public function __construct(Client $redisClient, Session $session)
    {
        $this->redisClient = $redisClient;
        $this->session = $session;
        $this->sessionUserId = $this->session->get('userId');
    }

    /**
     * @param integer $userId
     */
    private function follow($userId)
    {
        $this->redisClient->sadd("uid:".$userId.":followers", [
            $this->sessionUserId,
        ]);
        $this->redisClient->sadd("uid:".$this->sessionUserId.":following", [
            $userId,
        ]);
    }

    /**
     * @param int $userId
     */
    private function unfollow($userId)
    {
        $this->redisClient->srem("uid:".$userId.":followers", [
            $this->sessionUserId,
        ]);
        $this->redisClient->srem("uid:".$this->sessionUserId.":following", [
            $userId,
        ]);
    }

    /**
     * @param int $userId
     */
    public function followOrUnfollow($userId)
    {
        if ($this->sessionUserId === $userId) {
            return;
        }
        if (!$this->redisClient->sismember("uid:".$this->sessionUserId.":following", $userId)) {
            $this->follow($userId);
        } else {
            $this->unfollow($userId);
        }
    }

    /**
     * @return int
     */
    public function getFollowers()
    {
        return $this->redisClient->scard("uid:".$this->sessionUserId.":followers");
    }

    /**
     * @return int
     */
    public function getFollowing()
    {
        return $this->redisClient->scard("uid:".$this->sessionUserId.":following");
    }
}
