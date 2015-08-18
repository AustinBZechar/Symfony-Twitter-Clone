<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /**
     * @param int $userId
     *
     * @return Response
     * @Route("/profile/{userId}", name="profile")
     */
    public function profileAction($userId)
    {
        return $this->render(':default:profile.html.twig', [
            'tweets' => $this->get('app.redis.redis_tweet')->showUserPosts($userId),
            'userId' => $userId,
        ]);
    }

    /**
     * @param int $userId
     *
     * @return Response
     * @Route("/follow/{userId}", name="follow")
     */
    public function followAction($userId)
    {
        $this->get('app.redis.redis_follow')->followOrUnfollow($userId);

        return $this->redirectToRoute('profile', [
            'userId' => $userId,
        ]);
    }
}
