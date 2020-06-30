<?php

namespace App\Repository;

use App\Entity\Likes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    public function getNumberLikes($id)
    {
        $request = "SELECT COUNT(*) AS nbLikes FROM likes where content_id='".$id."' and likes.type='Like';"; 
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetch();
    }
}
