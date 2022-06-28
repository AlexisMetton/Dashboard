<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('alexis');
        $user->setPassword($this->encoder->encodePassword($user, 'test'));
        $manager->persist($user);

        $manager->flush();

        $user1 = new User();
        $user1->setUsername('ludovic');
        $user1->setPassword($this->encoder->encodePassword($user1, 'test'));
        $manager->persist($user1);

        $manager->flush();

        $user2 = new User();
        $user2->setUsername('enola');
        $user2->setPassword($this->encoder->encodePassword($user2, 'test'));
        $manager->persist($user2);

        $manager->flush();
    }
}
