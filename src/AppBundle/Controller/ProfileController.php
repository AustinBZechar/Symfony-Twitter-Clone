<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @param int     $userId
     *
     * @return Response
     * @Route("/profile/{userId}", name="profile")
     */
    public function profileAction(Request $request, $userId)
    {
        return $this->render(':default:profile.html.twig', [
            'tweets' => $this->get('app.redis.redis_tweet')->showUserPosts(),
            'userId' => $userId,
        ]);
    }

    /**
     * @param Request $request
     * @param int     $userId
     *
     * @return Response
     * @Route("/follow/{userId}", name="follow")
     */
    public function followAction(Request $request, $userId)
    {
        $this->get('app.redis.redis_follow')->followOrUnfollow($userId);

        return $this->render(':default:profile.html.twig', [
            'tweets' => $this->get('app.redis.redis_tweet')->showUserPosts(),
            'userId' => $userId,
        ]);
    }
}
