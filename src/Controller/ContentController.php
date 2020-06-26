<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentController extends AbstractController
{
    /**
     * @Route("/espace_membre/nouveau_contenu", name="content_create")
     */
    public function create(Request $request, EntityManagerInterface $manager, ContentRepository $contentRepo)
    {
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $content->setCreatedAt( new \DateTime())
                    ->setUser($this->getUser())
                    ->setMediaPath(null)
                    ->setStatus("En attente de vérifications");
            $manager->persist($content);
            $manager->flush();
            return $this->redirectToRoute('content_view', [
                'id' => $content->getId()
            ]);
        }
        return $this->render('content/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/espace_membre/contenus/{id}", name="content_view")
     */
    public function view(Request $request, ContentRepository $contentRepo, $id)
    {
        return $this->render('content/view.html.twig',[
            'content' => $contentRepo->find($id)
        ]);
    }
}
