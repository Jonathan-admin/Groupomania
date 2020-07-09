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

    /**
    * Retourner le nombre de likes et dislikes d'un contenu particulier
    */
    public function getNumberLikes($id)
    {
        $request = "SELECT (SELECT COUNT(*) FROM likes WHERE likes.content_id=".$id." AND likes.type='Like') AS nbLikes, 
        (SELECT COUNT(*) FROM likes WHERE likes.content_id=".$id." AND likes.type='Dislike') AS nbDislikes
        FROM likes LIMIT 1;";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetch();
    }

    /**
    * Suppression d'un like d'un contenu par un utilisateur
    */
    public function deleteLike($id, $author) {
        $request = "DELETE FROM likes where likes.content_id=".$id." and likes.author='".$author."';";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }
}
