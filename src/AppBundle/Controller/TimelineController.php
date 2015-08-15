<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TimelineController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/timeline", name="timeline")
     */
    public function timelineAction(Request $request)
    {
        return $this->render(':default:timeline.html.twig');
    }
}
