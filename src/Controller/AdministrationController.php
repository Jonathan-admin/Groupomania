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
     * @Route("/admin/gestion_utilisateur/{id}", name="admin_userView")
     */
    public function userView(UserRepository $userRepo, $id) {
        return $this->render('admin/userInfo.html.twig', [
            'user' =>  $userRepo->find($id)
        ]);
    }

     /**
     * @Route("/admin/gestion_utilisateur/{id}/droits", name="admin_modifyRoles")
     */
    public function modifyRules(UserRepository $userRepo, $id, Request $request) {
        $role = $request->request->get('role');
        $userRepo->updateRoles($id,$role);
        return $this->render('admin/userInfo.html.twig', [
            'user' =>  $userRepo->find($id)
        ]);
    }

     /**
     * @Route("/admin/gestion_utilisateur/{id}/suppression", name="admin_deleteUser")
     */
    public function deleteUser(UserRepository $userRepo, $id, Request $request) {
        $users = $userRepo->deleteUser($id);
        $users = $userRepo->getAllUsersWidthIdName();
        return $this->json(['status' => '200','users' => $users]);
    }
}
