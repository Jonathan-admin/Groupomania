<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrationController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_page")
     */
    public function admin(UserRepository $userRepo) {
        return $this->render('admin/page.html.twig', [
            'allUsers' =>  $userRepo->findAll()
        ]);
    }

    /**
     * @Route("/admin/gestion_utilisateur/{id}", name="admin_userView", requirements={"id"="\d+"})
     */
    public function userView(UserRepository $userRepo, $id) {
        return $this->render('admin/userInfo.html.twig', [
            'user' =>  $userRepo->find($id)
        ]);
    }

     /**
     * @Route("/admin/gestion_utilisateur/{id}/droits", name="admin_modifyRoles", requirements={"id"="\d+"})
     */
    public function modifyRules(UserRepository $userRepo, $id, Request $request) {
        $role = $request->request->get('role');
        $userRepo->updateRoles($id,$role);
        return $this->render('admin/userInfo.html.twig', [
            'user' =>  $userRepo->find($id)
        ]);
    }

     /**
     * @Route("/admin/gestion_utilisateur/{id}/suppression", name="admin_deleteUser", requirements={"id"="\d+"})
     */
    public function deleteUser(UserRepository $userRepo, $id) {
        $users = $userRepo->deleteUser($id);
        $users = $userRepo->getAllUsersWidthIdNameRole();
        return $this->json(['status' => '200','users' => $users]);
    }

    /**
     * @Route("/admin/gestion_utilisateur/actualisation_liste_utilisateurs", name="admin_refresh")
     */
    public function refreshListUsers(UserRepository $userRepo) {
        $users = $userRepo->getAllUsersWidthIdNameRole();
        return $this->json(['status' => '200','users' => $users]);
    }
}
