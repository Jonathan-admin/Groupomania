<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
            dump($content);
            $content->setCreatedAt( new \DateTime())
                    ->setUsername($this->getUser())
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

    /**
     * Retourne les contenus filtrés par l'utilisateur
     * @Route("/forum/Rechercher_des_contenus", name="filterContentsView")
     * @return void
     */
    public function filterSearchDisplay(Request $request, ContentRepository $contentRepo, PaginatorInterface $paginator)
    { 
        $session = $request->getSession();
        $valParametersFilterContents = $session->get('SearchparametersContent');
        $searchContentRequest = $contentRepo->requetefilterBuilder($valParametersFilterContents['type'],$valParametersFilterContents['topic'],
        $valParametersFilterContents['author'],$valParametersFilterContents['title'], $valParametersFilterContents['sorting']);
        $contentsSearchPagination = $paginator->paginate(
            $searchContentRequest,
            $request->query->getInt('page',1),
            8
        ); 
        return $this->render('content/searchDisplay.html.twig', [
            array('session' => $session),
            'contentsSearchPaginate' => $contentsSearchPagination
        ]);
    } 

     /**
     * Retourne les contenus filtrés par l'utilisateur
     * @Route("/preparation-de-la-recherche", name="filterContentsSet")
     * @return void
     */
    public function setParametersFilterSearchContents(Request $request) 
    {
        $session = $request->getSession();
        $parameters = Array(
            'type' => $request->request->get('type'),
            'topic' => $request->request->get('topic'),
            'author' => $request->request->get('author'),
            'title' => $request->request->get('title'),
            'sorting' => $request->request->get('sorting')
        );
        $session->set('SearchparametersContent',$parameters);

        return $this->redirectToRoute('filterContentsView');
    }
}
