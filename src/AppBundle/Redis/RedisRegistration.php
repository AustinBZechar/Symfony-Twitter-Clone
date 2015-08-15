<?php

namespace AppBundle\Redis;

use AppBundle\Exception\UsernameExistsException;
use AppBundle\Utils\RandomizeTrait;
use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class RedisRegistration
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

        $this->redisClient->sadd("global:users", [
            $userId,
        ]);
        $this->session->set('userId', $userId);
        $this->session->set('username', $username);

        return $authSecret;
    }
}
