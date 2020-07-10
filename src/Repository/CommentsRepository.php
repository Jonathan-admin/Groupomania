<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

     /**
      * @return Comments[] Returns an array of Comments objects
     */
    
    /**
    * Retourner tous les commentaires d'un contenu
    */
    public function findByContentId($id)
    {
        return $this->createQueryBuilder('comments')
            ->where('comments.Content = :id')
            ->setParameter('id', $id)
            ->orderBy('comments.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
