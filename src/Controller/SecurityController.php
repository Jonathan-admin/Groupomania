<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{ 
    
    /**
     * Afficher le forumlaire d'inscription et gérer le traitement
     * 
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash)
                ->setSubscribeAt( new \DateTime())
                ->setRolesUser(array("ROLE_USER"));
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Afficher la page d'autehtification et de validation
     * 
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $utils) {
        $error = $utils->getLastAuthenticationError();
        $lastNameUse = $utils->getLastUsername();
        return $this->render('security/login.html.twig',[
            'error' => $error,
            'lastUserName' => $lastNameUse
        ]);
    }

    /**
     * Déconnexion de l'utilisateur courant
    * @Route("/deconnexion", name="security_logout")
    */
    public function logout() {
    } 
} 
