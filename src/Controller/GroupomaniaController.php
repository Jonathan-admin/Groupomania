<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GroupomaniaController extends AbstractController
{
    /**
     * @Route("/", name="groupomania_home")
     */
    public function index()
    {
        return $this->render('groupomania/home.html.twig');
    }
}
