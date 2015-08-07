<?php

namespace AppBundle\Redis;

use Predis\Client;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class RedisLogin
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
}
