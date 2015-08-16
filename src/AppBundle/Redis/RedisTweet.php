<?php

namespace AppBundle\Redis;

use Predis\Client;
use Symfony\Component\HttpFoundation\Session\Session;

class RedisTweet
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
     * @param string $status
     */
    public function tweet($status)
    {
        $postId = $this->redisClient->incr("global:nextPostId");
        $userId = $this->session->get('userId');
        $post = $userId."|".time()."|".$status;
        $this->redisClient->set("post:$postId", $post);
        $followers = $this->redisClient->smembers("uid:".$userId.":followers");
        if ($followers === false) {
            $followers = [];
        }

        $followers[] = $userId; /* Add the post to our own posts too */

        foreach ($followers as $fid) {
            $this->redisClient->lpush("uid:$fid:posts", [
                $postId,
            ]);
        }
        # Push the post on the timeline, and trim the timeline to the
        # newest 1000 elements.
        $this->redisClient->lpush("global:timeline", [
            $postId,
        ]);
        $this->redisClient->ltrim("global:timeline", 0, 1000);
    }

    /**
     * @return array
     */
    public function showUserPosts()
    {
        $userId = $this->session->get('userId');
        $key = ($userId == -1) ? "global:timeline" : "uid:$userId:posts";
        // TODO maybe use start & count or do ajax call and increase start and count
        $posts = $this->redisClient->lrange($key, 0, 10);
        $postData = [];
        foreach ($posts as $post) {
            $postData[] = $this->redisClient->get("post:$post");
        }

        return $postData;
    }

    /**
     * @return array
     */
    public function showLastUsers()
    {
        return $this->redisClient->sort("global:users", [
            "GET uid:*:username DESC LIMIT 0 10",
        ]);
    }
}
