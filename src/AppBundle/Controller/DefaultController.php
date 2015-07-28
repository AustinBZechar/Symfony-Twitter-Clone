<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        $redis = $this->container->get('snc_redis.default');
        $incr = $redis->incr('foo:bar');
        $get = $redis->get('foo:bar');
        return $this->render('default/index.html.twig', [
            'incr' => $incr,
            'get'  => $get,
        ]);
    }
}
