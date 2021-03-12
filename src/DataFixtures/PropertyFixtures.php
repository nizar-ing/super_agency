<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); // create a French faker
        for($i=0; $i<100; $i++){
            $property = new Property();
            $property->setTitle($faker->words(4, true))
                     ->setDescription($faker->sentences(3, true))
                     ->setSurface($faker->numberBetween(20,450))
                     ->setRooms($faker->numberBetween(2, 10))
                     ->setBedrooms($faker->numberBetween(1,8))
                     ->setFloor($faker->numberBetween(0, 15))
                     ->setPrice($faker->numberBetween(100000, 1000000))
                     ->setHeat($faker->numberBetween(1, count(Property::HEAT) - 1))
                     ->setCity($faker->city)
                     ->setAddress($faker->address)
                     ->setPostalCode($faker->postcode)
                     ->setSold($faker->numberBetween(0,1));

            $manager->persist($property);
        }
        $manager->flush();
    }
}
