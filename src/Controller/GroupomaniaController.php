<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupomaniaController extends AbstractController
{
    /**
     * @Route("/", name="groupomania_home")
     */
    public function home()
    {
        return $this->render('groupomania/home.html.twig');
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
