<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Property;

class PropertyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++)
        {
            $property = new Property();
            $property
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentences(3, true))
                ->setPrix($faker->numberBetween(100, 50000))
                ->setDateAchat(new \DateTime())
                ->setDateGarantie(new \DateTime())
                ->setIdCategorie($faker->numberBetween(1,4))
                ->setLieu($faker->numberBetween(1,2))
                ->setImage("google-logo.jpg");

                $manager->persist($property);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
