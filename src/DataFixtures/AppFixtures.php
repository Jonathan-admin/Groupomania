<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use App\Entity\Content;
use App\Entity\Likes;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Json;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

        $topic = array("Général", "Voyages", "Techonologies", "Sports", "Loisirs","Monde professionnel", "Economie", "Groupomania", "Science", "Actualité","Divers");
        $type = array("Texte", "Vidéo", "Musique", "Image");
        $status = array("En attente de vérifications", "Bloqué", "Vérifié", "Suspendu");
        $likesType = array("Like", "Dislike");

        for($i=1;$i<=10;$i++) {

            $user = new User();
            $hash = $this->encoder->encodePassword($user,$i."Km@pieds");
            $user->setUsername($faker->userName())
                 ->setPassword($hash)
                 ->setEmail($faker->email())
                 ->setSubscribeAt($faker->dateTimeBetween('- 6 months'));
            if($i==1) {
                $user->setRolesUser(array("ROLE_DEL_ADMIN"));
            } else if ($i%2 == 0) {
                $user->setRolesUser(array("ROLE_USER","ROLE_ADMIN"));
            } else {
                $user->setRolesUser(array("ROLE_USER"));
            }
            $manager->persist($user);

            for($j=1;$j<=mt_rand(0,5);$j++) {
                $content = new Content();
                $content->setUsername($user)
                        ->setTitle($faker->sentence(8))
                        ->setMessage("".join($faker->paragraphs(5))."")
                        ->setTopic($topic[array_rand($topic, 1)]);
                $typeContent = $type[array_rand($type, 1)];
                $content->setType($typeContent);
                if($typeContent == "Texte") {   
                        $content->setMediaPathUrl(null)
                            ->setMediaPathFile(null);
                } else if ($typeContent == "Image") {
                    $arrayMediaPathFile = explode("/",$faker->image($dir = __DIR__.'/../../public/uploadFile/images/',$width=640,$height=480));
                    $content->setMediaPathUrl(null)
                            ->setMediaPathFile($arrayMediaPathFile[count($arrayMediaPathFile)-1]);
                } else if ($typeContent == "Musique") {
                    $arrayMediaPathFile = explode("/",$faker->file($sourceDir  = __DIR__.'/../../public/uploadFile/fixtures/',
                    $targetDir = __DIR__.'/../../public/uploadFile/musics/'));
                    $content->setMediaPathUrl(null)
                            ->setMediaPathFile($arrayMediaPathFile[count($arrayMediaPathFile)-1]);
                } else if ($typeContent == "Vidéo") {
                    $content->setMediaPathUrl("https://www.youtube.com/embed/bBt4o5zrtJQ")
                            ->setMediaPathFile(null);
                } else {
                    $content->setMediaPathUrl(null)
                            ->setMediaPathFile(null);
                }
                $content->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                    ->setStatus($status[array_rand($status, 1)]);
                $manager->persist($content);

                for($k=1;$k<=mt_rand(0,10);$k++){
                    $likes = new Likes();
                    $likes->setContent($content)
                         ->setType($likesType[array_rand($likesType, 1)])
                         ->setAuthor($faker->userName())
                         ->setLikedAt($faker->dateTimeBetween('- 6 months'));
                    $manager->persist($likes);
                }

                for($k=1;$k<=mt_rand(0,10);$k++){
                    $comments = new Comments();
                    $comments->setContent($content)
                             ->setMessage("".join($faker->paragraphs(2))."")
                             ->setAuthor($faker->userName())
                             ->setCreatedAt($faker->dateTimeBetween('- 6 months'));
                    $manager->persist($comments);
                }
            }
        }
        $manager->flush();
    }
}
