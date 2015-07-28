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
        $val = $redis->incr('foo:bar');
        var_dump($val);
        $redis_cluster = $this->container->get('snc_redis.cluster');
        $val = $redis_cluster->get('ab:cd');
        var_dump($val);
        $val = $redis_cluster->get('ef:gh');
        var_dump($val);
        $val = $redis_cluster->get('ij:kl');
        var_dump($val);
        die('OK');
        return $this->render('default/index.html.twig');
    }
}
