<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupomaniaController extends AbstractController
{
    /**
     * @Route("/", name="groupomania_home")
     */
    public function home(ContentRepository $contentRepo, PaginatorInterface $paginator, Request $request)
    {
        $paginationCollection = $paginator->paginate(         
            $contentRepo->getAllContentForHome(),
            $request->query->getInt('page', 1),
            6
        );  
        return $this->render('groupomania/home.html.twig', [
            'Allcontents' => $paginationCollection,
            'popularContents' => $contentRepo->getPopularContent()
        ]);
    }

     /**
     * @Route("/espace_membre", name="groupomania_space_member")
     */
    public function spaceMember()
    {
        return $this->render('groupomania/space_member.html.twig');
    }

     /**
     * @Route("/notes", name="groupomania_infos")
     */
    public function informationsPage()
    {
        return $this->render('groupomania/notes.html.twig');
    }
}
