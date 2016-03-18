<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $response = $this->render(
            'default/index.html.twig', 
            [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            ]
        );
        $response->setETag(md5($response->getContent()));
        $response->isNotModified($request);
        $response->setMaxAge(10);
        $response->setTtl(10);
        $response->setPublic();
        // replace this example code with whatever you need
        return $response;
    }

    
    public function esiAction(Request $request)
    {
        $response = $this->render('default/esi.html.twig');
        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge(5);

        return $response;
    }

    /**
     * @Route("/pass", name="pass")
     */
    public function passedAction(Request $reques)
    {
        $response = new Response();
        $response->setMaxAge(10);
        $response->setPublic();
        $response->setTtl(10);
        // replace this example code with whatever you need
        return $this->render(
            'default/index.html.twig', 
            [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            ],
            $response
        );
    }
}
