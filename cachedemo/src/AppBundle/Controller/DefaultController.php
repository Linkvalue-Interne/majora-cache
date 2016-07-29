<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $age = 15;
        $response = $this->render(
            'default/index.html.twig',
            [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
                'age' => $age
            ]
        );
        //$response->setETag(md5($response->getContent()));
        // $response->isNotModified($request);
        $response->setSharedMaxAge($age);

        return $response;
    }

    public function esiAction(Request $request)
    {
        $age = 5;
        $response = $this->render('default/esi.html.twig', ['age' => $age]);

        // set the shared max age - which also marks the response as public
        $response->setSharedMaxAge($age);

        return $response;
    }

    public function etagAction(Request $request)
    {
        $filename = sprintf(
                '%s/data/etag.txt',
                $this->getParameter('kernel.root_dir') 
            );

        $form = $this->createFormEtag();

        $contentFile = file_get_contents($filename);

        
        $response = $this->render(
            'default/etag.html.twig',
            [
                'content' => $contentFile,
                'form' => $form->createView(),
            ]
        );
        $response->setPublic(true);
        $response->setETag(md5($response->getContent()));

        return $response;
    }

    public function contentCreateAction(Request $request)
    {
        $filename = sprintf(
                '%s/data/etag.txt',
                $this->getParameter('kernel.root_dir') 
            );
        $form = $this->createFormEtag();
        
        $form->handleRequest($request);
        if ($form->isValid()) {
            $content = $form->get('content')->getData();
            
            $handle = fopen($filename, 'w+');
            fwrite($handle, $content);
            fclose($handle);
        }
        
        return $this->redirect($this->generateUrl('app_etag'));
    }

    private function createFormEtag()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('app_content_create'))
            ->add('content', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Save'])
            ->getForm();

        return $form;
    }
}
