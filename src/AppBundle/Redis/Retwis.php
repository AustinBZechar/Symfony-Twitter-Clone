<?php

namespace AppBundle\Redis;

use Predis\Client;

class Retwis
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

    public function getRand()
    {
        $fd = fopen("/dev/urandom","r");
        $data = fread($fd,16);
        fclose($fd);
        return md5($data);
    }

}