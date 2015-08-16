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
     * @param Client $redisClient
     * @param Session $session
     */
    public function __construct(Client $redisClient, Session $session)
    {
        $this->redisClient = $redisClient;
        $this->session = $session;
    }

    /**
     * @param integer $userId
     */
    private function follow($userId)
    {
        $sessionUserId = $this->session->get('userId');
        if ($userId != $sessionUserId) {
            $this->redisClient->sadd("uid:".$userId.":followers", [
                $sessionUserId,
            ]);
            $this->redisClient->sadd("uid:".$sessionUserId.":following", [
                $userId,
            ]);
        }
    }

    /**
     * @param int $userId
     */
    private function unfollow($userId)
    {
        $sessionUserId = $this->session->get('userId');
        if ($userId != $sessionUserId) {
            $this->redisClient->srem("uid:".$userId.":followers", [
                $sessionUserId,
            ]);
            $this->redisClient->srem("uid:".$sessionUserId.":following", [
                $userId,
            ]);
        }
    }

    /**
     * @param int $userId
     */
    public function followOrUnfollow($userId)
    {
        $sessionUserId = $this->session->get('userId');
        if ($this->redisClient->sismember("uid:".$sessionUserId.":following", $userId) === 1) {
            $this->follow($userId);
        }

        $this->unfollow($userId);
    }

    /**
     * @return int
     */
    public function getFollowers()
    {
        return $this->redisClient->scard("uid:".$this->session->get('userId').":followers");
    }

    /**
     * @return int
     */
    public function getFollowing()
    {
        return $this->redisClient->scard("uid:".$this->session->get('userId').":following");
    }
}
