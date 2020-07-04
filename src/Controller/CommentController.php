<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Repository\ContentRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * Créer un nouveau commentaire
     * @Route("/forum/contenu/{id}/creation_commentaire", name="comment_create")
     */
    public function create($id, EntityManagerInterface $manager, Request $request, ContentRepository $contentRepo, CommentsRepository $commentRepo) 
    {
        $comment = new Comments();
        $comment->setAuthor($this->getUser()->getUsername())
                ->setMessage($request->request->get('message'))
                ->setCreatedAt(new \DateTime())
                ->setContent($contentRepo->find($id));
        $manager->persist($comment);
        $manager->flush();
        return $this->redirectToRoute('comment_view',['id' => $id]);
    }

     /**
     * Supprimer un commentaire
     * @Route("/forum/contenu/{id}/suppression_commentaire/{idComment}", name="comment_delete")
     */
    public function delete($idComment, $id, EntityManagerInterface $manager, CommentsRepository $commentRepo) 
    {
        $omment = $commentRepo->find($idComment);
        $manager->remove($omment);
        $manager->flush();
        return $this->redirectToRoute('comment_view',['id' => $id]);
    }

    /**
     * Afficher les commentaires
     * @Route("/forum/contenu/{id}/afficher_commentaires", name="comment_view")
     */
    public function view($id, CommentsRepository $commentRepo) 
    {
        return $this->render('comment/view.html.twig', [
            'allComments' => $commentRepo->findByContentId($id),
            'idContent' => $id
        ]);
    }
}
