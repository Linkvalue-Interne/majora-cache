<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $age = 10;
        $response = $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
                'age' => $age
            ]
        );
        $response->setETag(md5($response->getContent()));
        $response->isNotModified($request);
        $response->setMaxAge($age);
        $response->setTtl($age);
        // $response->setPublic();
        // replace this example code with whatever you need
        return $response;
    }

    public function esiAction(Request $request)
    {
        $age = 3;
        $response = $this->render('default/esi.html.twig', ['age' => $age]);

        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge($age);

        return $response;
    }

    public function passedAction(Request $request)
    {
        $response = new Response();
        $response->setMaxAge(10);
        $response->setPublic();
        $response->setTtl(10);
        // replace this example code with whatever you need
        $response = $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
                'age' => 0
            ]
        );

        return $response;
    }
}
