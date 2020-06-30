<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\Likes;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\CommentsRepository;
use App\Repository\LikesRepository;
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
    public function create(Request $request, Content $content = null, EntityManagerInterface $manager)
    {  
        if(!$content) { 
            $content = new Content();
            $mediaPathFile = null;
        } else {
            $mediaPathFile = $content->getMediaPathFile();
        }
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $type = $request->request->get('content')['type'];
            if($type=="Image"||$type=="Musique") {
                $file = $form['mediaPathFile']->getData();
                $content->setFiles($file)
                        ->setMediaPathFile($content->uploadMediaFile($mediaPathFile,$type))
                        ->setMediaPathUrl(null);
            } else if($type=="Vidéo") {
                $content->setMediaPathFile(null);
            } 
            $content->setUsername($this->getUser())
                    ->setStatus("En attente de vérifications") ;      
            if(!$content->getId()) {
                $content->setCreatedAt(new \DateTime());
            }
            $manager->persist($content);
            $manager->flush();
            return $this->redirectToRoute('content_view',[
                'id' => $content->getId()
            ]);
        }
        return $this->render('content/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/forum/contenu/{id}", name="content_view")
     */
    public function view(ContentRepository $contentRepo, CommentsRepository $commentRepo, $id)
    {       
        return $this->render('content/view.html.twig',[
            'content' => $contentRepo->viewContentLikesComments($id,$this->getUser()->getUsername()),
            'allComments' => $commentRepo->findByContentId($id)
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

      /**
     * Like le contenu visualisé
     * @Route("/forum/contenu/{id}/like", name="content_like")
     */
    public function contentLike($id, EntityManagerInterface $manager, LikesRepository $likesRepo, ContentRepository $contentRepo) 
    {
        $like = new Likes();
        $like->setLikedAt( new \DateTime())
             ->setType("Like")
             ->setAuthor($this->getUser()->getUsername())
             ->setContent($contentRepo->find($id));
        $manager->persist($like);
        $manager->flush();
        return $this->json(['nbLikes' => $likesRepo->getNumberLikes($id)]);   
    }
}
