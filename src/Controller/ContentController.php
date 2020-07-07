<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\LikesRepository;
use App\Repository\ContentRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContentController extends AbstractController
{
    /**
     * @Route("/espace_membre/nouveau_contenu", name="content_create")
     * @Route("/espace_membre/modification_contenu/{id}", name="content_modify")
     */
    public function edit(Request $request, Content $content = null, EntityManagerInterface $manager)
    {  
        if(!$content) { 
            $content = new Content();
            $mediaPathFile = null;
            $mediaPathUrl = null;
        } else {
            $mediaPathFile = $content->getMediaPathFile();
            $mediaPathUrl = $content->getMediaPathUrl();
            $oldType = $content->getType();
        }
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $type = $request->request->get('content')['type'];
            $file = $form['mediaPathFile']->getData();
            if($type=="Image"||$type=="Musique") {
                $content->setMediaPathUrl(null);
                if($file) {
                    if($mediaPathFile != "") {
                        $content->deleteFile($mediaPathFile,$oldType);
                    }
                    $content->setFiles($file)
                            ->setMediaPathFile($content->uploadMediaFile($mediaPathFile,$type));
                } else {
                    $content->setMediaPathFile($mediaPathFile);
                }
            } else {
                if($mediaPathFile) {
                    $content->deleteFile($mediaPathFile,$oldType);
                    $content->setMediaPathFile(null);
                }
            }
            $content->setUsername($this->getUser())
                    ->setStatus("En attente de vérifications");           
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
            'form' => $form->createView(),
            'mediaPathFile' => $mediaPathFile,
            'mediaPathUrl' => $mediaPathUrl,
            'idContent' => $content->getId()
        ]);
    }

    /**
     * @Route("/espace_membre/supprimer_contenu/{id}", name="content_delete")
     */
    public function delete(ContentRepository $contentRepo, $id, EntityManagerInterface $manager)
    {     
        $contentObjet = $contentRepo->find($id);
        if($mediaPathFile = $contentObjet->getMediaPathFile()) {
            $contentObjet->deleteFile($mediaPathFile,$contentObjet->getType());
        }
        $manager->remove($contentObjet);
        $manager->flush();
        return $this->redirectToRoute('groupomania_spaceMember');
    }

     /**
     * @Route("/forum/contenu/{id}", name="content_view")
     */
    public function view(ContentRepository $contentRepo, CommentsRepository $commentRepo, $id)
    {       
        return $this->render('content/view.html.twig',[
            'content' => $contentRepo->viewContentLikesComments($id,$this->getUser()->getUsername()),
            'allComments' => $commentRepo->findByContentId($id),
            'idContent' => $id
        ]);
    }

    /**
     * Retourne les contenus filtrés par l'utilisateur
     * @Route("/forum/Rechercher_des_contenus", name="content_filterView")
     * @return void
     */
    public function filterView(Request $request, ContentRepository $contentRepo, PaginatorInterface $paginator)
    { 
        $session = $request->getSession();
        $valParametersFilterContents = $session->get('SearchparametersContent');
        $searchContentRequest = $contentRepo->requetefilterBuilder($valParametersFilterContents['type'],$valParametersFilterContents['topic'],
        $valParametersFilterContents['author'],$valParametersFilterContents['title'], $valParametersFilterContents['sorting']);
        if($searchContentRequest != null) {
            $contentsSearchPagination = $paginator->paginate(
                $searchContentRequest,
                $request->query->getInt('page',1),
                8
            ); 
        } else {
            $contentsSearchPagination = null;
        }
        return $this->render('content/searchDisplay.html.twig', [
            array('session' => $session),
            'contentsSearchPaginate' => $contentsSearchPagination
        ]);
    } 

     /**
     * Retourne les contenus filtrés par l'utilisateur
     * @Route("/preparation-de-la-recherche", name="content_filterSession")
     * @return void
     */
    public function filterSession(Request $request) 
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

        return $this->redirectToRoute('content_filterView');
    }

      /**
     * Like le contenu visualisé
     * @Route("/forum/contenu/{id}/like", name="content_like")
     */
    public function like($id, Request $request, EntityManagerInterface $manager, LikesRepository $likesRepo, ContentRepository $contentRepo) 
    {
        $role = $request->request->get('role');
        if($role!=0) {
            $like = new Likes();
            if($role == 1) {
                $role = "Like";
                $isLikeAction = "isLiked";
            } else {
                $role = "Dislike";
                $isLikeAction = "isDisliked";
            }
            $like->setLikedAt( new \DateTime())
                 ->setType($role)
                 ->setAuthor($this->getUser()->getUsername())
                 ->setContent($contentRepo->find($id));
            $manager->persist($like);
            $manager->flush();
        } else {
            $isLikeAction = "canceledLike";
            $likesRepo->deleteLike($id,$this->getUser()->getUsername());
        }
        return $this->render('content/likesArea.html.twig', [
            'IsLikeAction' => $isLikeAction,
            'nbDislikesLikes' => $likesRepo->getNumberLikes($id),
            'contentUser' => $request->request->get('contentUser'),
            'id' => $id
        ]);
    }
}
