<?php

namespace AppBundle\Controller;

use AppBundle\Form\RegistrationType;
use AppBundle\Transfer\LoginTransfer;
use AppBundle\Transfer\RegistrationTransfer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    const HOUR_IN_SECONDS = 3600;
    const DAY_IN_HOURS = 24;
    const YEAR_IN_DAYS = 365;

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $registrationTransferObject = new RegistrationTransfer();
        $registrationForm = $this->initForm($request, $registrationTransferObject);

        $loginTransferObject = new LoginTransfer();
        $loginForm = $this->initForm($request, $loginTransferObject);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $authSecret = $this->get('app.redis.redis_registration')->register(
                $registrationTransferObject->getUsername(),
                $registrationTransferObject->getPassword()
            );

            return $this->generateCookieResponse($authSecret);
        }

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $authSecret = $this->get('app.redis.redis_registration')->register(
                $loginTransferObject->getUsername(),
                $loginTransferObject->getPassword()
            );

            return $this->generateCookieResponse($authSecret);
        }

        return $this->render(':default:index.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param $transferObject
     *
     * @return Form
     */
    private function initForm(Request $request, $transferObject)
    {
        $form = $this->createForm(new RegistrationType(), $transferObject);
        $form->handleRequest($request);
        return $form;
    }

    /**
     * @param $authSecret
     *
     * @return Response
     */
    private function generateCookieResponse($authSecret)
    {
        $cookie = new Cookie(
            'auth',
            $authSecret,
            new \DateTime(self::HOUR_IN_SECONDS * self::DAY_IN_HOURS * self::YEAR_IN_DAYS)
        );

        $response = new Response($this->renderView(':default:home.html.twig'));
        $response->headers->setCookie($cookie);

        return $response;
    }
}
