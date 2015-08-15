<?php

namespace AppBundle\Controller;

use AppBundle\Form\TweetType;
use AppBundle\Transfer\TweetTransfer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    /**
     * @param Request $request
     * @param Cookie  $cookie
     *
     * @return Response
     *
     * @Route("/home", name="home")
     */
    public function homeAction(Request $request, Cookie $cookie = null)
    {
        $tweetTransferObject = new TweetTransfer();
        $form = $this->createForm(new TweetType(), $tweetTransferObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO
        }

        $response = new Response($this->renderView(':default:home.html.twig', [
            'tweetForm' => $form->createView(),
        ]));

        if ($cookie !== null) {
            $response->headers->setCookie($cookie);
        }

        return $response;
    }
}
