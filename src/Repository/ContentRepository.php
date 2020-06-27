<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function getAllContentForHome(){
        return $this->createQueryBuilder('content')
        ->select('content.title','content.topic','content.type','content.status')
        ->where('content.status = :status')
        ->setParameter('status','Vérifié')
        ->getQuery();
    }

    public function getPopularContent(){
        $request = "SELECT title, topic, type, status, nbComment, nbLikes, nbDislikes, nbLikes-nbDislikes AS sub
                   FROM ( SELECT content.title, content.topic, content.type, content.status,
                   (SELECT COUNT(*) FROM comments WHERE comments.content_id=content.id) AS nbComment,
                   (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Like') AS nbLikes, 
                   (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Dislike') AS nbDislikes 
                   FROM content WHERE content.status='Vérifié') AS vue ORDER BY sub DESC LIMIT 4";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetchAll();
    }
}
