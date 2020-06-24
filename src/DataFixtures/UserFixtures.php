<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 100; $i++) { 
            $user = new User();
            $hash = $this->encoder->encodePassword($user,$i."Km@pieds");
            $user->setPassword($hash);
            $user->setUsername("user".$i)
                ->setEmail("user".$i."@gmail.com")  
                ->setSubscribeAt(new \DateTime())
                ->setPassword($hash);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
