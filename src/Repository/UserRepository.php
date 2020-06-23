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

    public function getAllUsersWidthIdName()
    {
        return $this->createQueryBuilder('user')
            ->select('user.id','user.username')
            ->getQuery()
            ->getArrayResult();
    }

    public function deleteUser($id)
    {
        $request = "DELETE FROM user WHERE id = '".$id."'";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }

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
