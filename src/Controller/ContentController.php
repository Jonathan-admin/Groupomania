<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentController extends AbstractController
{
    /**
     * @Route("/espace_membre/nouveau_contenu", name="content_create")
     */
    public function create(Request $request)
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        return $this->render('content/create.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
