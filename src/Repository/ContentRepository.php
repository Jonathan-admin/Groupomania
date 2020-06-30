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
        $qb = $this->createQueryBuilder('content')
            ->select('content.id, content.title','content.topic','content.type','content.status');
        $qb->where($qb->expr()->in('content.status',array('Vérifié','Bloqué')));
        return $qb->getQuery();
    }

    public function getAllMyContents($user){
        return $this->createQueryBuilder('content')
                    ->innerJoin('content.username','user')
                    ->where('user.username = :user')
                    ->setParameter('user', $user)
                    ->orderBy('content.createdAt','DESC')
                    ->getQuery();
    }

    public function getPopularContent(){
        $request = "SELECT id, title, topic, type, status, nbComment, nbLikes, nbDislikes, nbLikes-nbDislikes AS sub
                   FROM ( SELECT content.id, content.title, content.topic, content.type, content.status,
                   (SELECT COUNT(*) FROM comments WHERE comments.content_id=content.id) AS nbComment,
                   (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Like') AS nbLikes, 
                   (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Dislike') AS nbDislikes 
                   FROM content WHERE content.status='Vérifié') AS vue ORDER BY sub DESC LIMIT 4";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetchAll();
    }

    function requetefilterBuilder($type,$topic,$author,$title,$sorting)
    {
        $qb = $this->createQueryBuilder('content')
                    ->innerJoin('content.username','user');
        $qb->where($qb->expr()->in('content.status',array('Vérifié','Bloqué')));
        if($type!="") {
            switch ($type) {
                case 'img/text':
                    $qb->andWhere($qb->expr()->in('content.type',array('Image','Texte')));
                    break;
                case 'mus/vid':
                    $qb->andWhere($qb->expr()->in('content.type',array('Musique','Vidéo')));
                    break;
                default:
                    $qb->andWhere('content.type = :type')
                    ->setParameter('type',$type);
                    break;
            }
        }
        if($topic!="") {
            $qb->andWhere('content.topic = :topic')
                ->setParameter('topic',$topic);
        }
        if($author!="") {
            $qb->andWhere($qb->expr()->like('user.username',':username'))
                ->setParameter('username','%'.$author.'%');
        }  
        if($title!="") {
            $qb->andWhere($qb->expr()->like('content.title',':title'))
                ->setParameter('title','%'.$title.'%');
        }      
        if($sorting=="new") { 
            $qb->orderBy('content.createdAt','DESC'); }
        elseif($sorting=="old") {
            $qb->orderBy('content.createdAt','ASC'); }
        elseif($sorting=="topic") {  
            $qb->orderBy('content.topic'); }
        elseif($sorting=="type") {
            $qb->orderBy('content.type'); }
        elseif($sorting=="alphaOrder"){
            $qb->orderBy('content.title','ASC'); 
        }
        elseif($sorting=="alphaOrderRev"){
            $qb->orderBy('content.title','DESC'); 
        }
        return $qb->getQuery();  
    }
    
    public function getMyStatisticContents($user){
        $request = "SELECT content.title, content.status, content.type, content.created_at,
                    (SELECT COUNT(*) FROM comments WHERE comments.content_id=content.id) AS nbComment,
                    (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Like') AS nbLikes,
                    (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Dislike') AS nbDislikes 
                    FROM content WHERE content.username_id='".$user."' ORDER BY content.created_at;";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function getInfosContentUser($id){
        return $this->createQueryBuilder('content')
                    ->innerJoin('content.username','user')
                    ->where('content.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function updateStatus($id,$status)
    {
        switch ($status) {
            case "checked":
                $status = "Vérifié";
                break;
            case "pending":
                $status = "Vérification en attente";
                break;
            case "suspended":
                $status = "Suspendu";
                break;
            case "blocked":
                $status = "Bloqué";
                break;
        }
        $request = "UPDATE content SET content.status = '".$status."' WHERE id = '".$id."'";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }

    public function deleteContent($id)
    {
        $request = "DELETE FROM content WHERE id = '".$id."'";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
    }

    public function getAllContentsWidthIdTitleStatus()
    {
        return $this->createQueryBuilder('content')
            ->select('content.id','content.title','content.status')
            ->getQuery()
            ->getArrayResult();
    }

    public function viewContentLikesComments($id,$user)
    {
        $request = "SELECT id, status, type, topic, title, message, media_path_url, media_path_file, created_at, username, nbComments, nbLikes, nbDislikes, isLiked, isDisliked
        FROM ( SELECT content.*, user.username,
        (SELECT COUNT(*) FROM comments WHERE comments.content_id=content.id) AS nbComments,
        (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Like') AS nbLikes, 
        (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Dislike') AS nbDislikes, 
        (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Like' AND likes.author='".$user."') AS isLiked,
        (SELECT COUNT(*) FROM likes WHERE likes.content_id=content.id AND likes.type='Dislike' AND likes.author='".$user."') AS isDisliked
        FROM content INNER JOIN user ON user.id=content.username_id )AS vue WHERE id=".$id.";";
        $statement =  $this->getEntityManager()->getConnection()->prepare($request);
        $statement->execute();
        return $statement->fetch();
    }
}

