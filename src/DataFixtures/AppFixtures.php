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

        for($i=1;$i<=100;$i++) {
            $user = new User();
            $hash = $this->encoder->encodePassword($user,$i."Km@pieds");
            $user->setUsername($faker->userName())
                 ->setPassword($hash)
                 ->setEmail($faker->email())
                 ->setSubscribeAt($faker->dateTimeBetween('- 6 months'));
            $manager->persist($user);

            for($j=1;$j<=mt_rand(0,25);$j++) {
                $content = new Content();
                $content->setUsername($user)
                        ->setTitle($faker->sentence(8))
                        ->setMessage("<p>".join($faker->paragraphs(5),"</p><p>")."<\p>")
                        ->setTopic($topic[array_rand($topic, 1)])
                        ->setType($type[array_rand($type, 1)])
                        ->setCreatedAt($faker->dateTimeBetween('- 6 months'))
                        ->setMediaPath(null)
                        ->setStatus($status[array_rand($status, 1)]);
                $manager->persist($content);

                for($k=1;$k<=mt_rand(0,100);$k++){
                    $likes = new Likes();
                    $likes->setContent($content)
                         ->setType($likesType[array_rand($likesType, 1)])
                         ->setAuthor($faker->userName())
                         ->setLikedAt($faker->dateTimeBetween('- 6 months'));
                    $manager->persist($likes);
                }

                for($k=1;$k<=mt_rand(0,100);$k++){
                    $comments = new Comments();
                    $comments->setContent($content)
                             ->setMessage("<p>".join($faker->paragraphs(3),"</p><p>")."<\p>")
                             ->setAuthor($faker->userName())
                             ->setCreatedAt($faker->dateTimeBetween('- 6 months'));
                    $manager->persist($comments);
                }
            }
        }
        $manager->flush();
    }
}
