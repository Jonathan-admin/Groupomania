<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
    * Retourner un tableau contenant tous les utilisateurs de l'application
    */
    public function getAllUsersWidthIdNameRole()
    {
        return $this->createQueryBuilder('user')
            ->select('user.id','user.username','user.roles')
            ->getQuery()
            ->getArrayResult();
    }

    /**
    * Suppression d'un utilisateur à partir de son id
    */
    public function deleteUser($id)
    {
        $request = "DELETE FROM user WHERE id = '".$id."'";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }

    /**
    * Mettre à jour le rôle d'un utilisateur en base de données
    */
    public function updateRoles($id,$role)
    {
        switch ($role) {
            case "use":
                $role = '["ROLE_USER"]';
                break;
            case "admin":
                $role = '["ROLE_USER","ROLE_ADMIN"]';
                break;
            case "block":
                $role = '[]';
                break;
        }
        $request = "UPDATE user SET user.roles = '".$role."' WHERE id = '".$id."'";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }

}
