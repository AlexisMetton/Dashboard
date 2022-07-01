<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class Categfixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0 ; $i < 100 ; $i++){

        $product = new Property();
            $product
                ->setIdCategorie($faker->numberbetween(1,4));




         $manager->persist($product);
}
        $manager->flush();
    }
}
