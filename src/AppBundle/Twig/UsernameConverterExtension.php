<?php

namespace AppBundle\Twig;

use Predis\Client;

class UsernameConverterExtension extends \Twig_Extension
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
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('username_converter', [
                $this,
                'usernameConverter',
            ]),
        ];
    }

    /**
     * @param int $userId
     *
     * @return string
     */
    public function usernameConverter($userId)
    {
        return $this->redisClient->get("uid:$userId:username");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'username_converter';
    }
}
