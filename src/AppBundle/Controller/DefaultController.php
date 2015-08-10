<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegistrationType;
use AppBundle\Transfer\RegistrationTransfer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    const HOUR_IN_SECONDS = 3600;
    const DAY_IN_HOURS    = 24;
    const YEAR_IN_DAYS    = 365;

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $transferObject = new RegistrationTransfer();
        $form = $this->createForm(new RegistrationType(), $transferObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authSecret = $this->get('app.redis.redis_registration')->register(
                $transferObject->getUsername(),
                $transferObject->getPassword()
            );

            $cookie = new Cookie(
                'auth',
                $authSecret,
                new \DateTime(self::HOUR_IN_SECONDS * self::DAY_IN_HOURS * self::YEAR_IN_DAYS)
            );

            $response = new Response($this->renderView(':default:home.html.twig'));
            $response->headers->setCookie($cookie);

            return $response;
        }

        return $this->render(':default:index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
