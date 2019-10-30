<?php

namespace App\DataFixtures;

use Faker\Factory;
use Liior\Faker\Prices;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BaseFixtures extends Fixture
{

    protected $faker;
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        $this->faker = $faker;

        $this->loadData($manager);

        $manager->flush();
    }

    protected function loadData(ObjectManager $manager)
    { }
}
