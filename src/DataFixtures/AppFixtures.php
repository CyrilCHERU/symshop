<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use App\Entity\Category;
use Bezhanov\Faker\Provider\Commerce;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(Slugify $slugify)
    {
        $this->slugger = $slugify;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for ($i = 0; $i < 6; $i++) {

            $category = new Category;

            $category->setTitle($faker->department());


            $manager->persist($category);

            for ($j = 0; $j < 10; $j++) {

                $product = new Product;

                $product->setTitle($faker->productName)
                    ->setPrice($faker->price(20, 500) * 100)
                    ->setImage($faker->imageUrl(100, 100))
                    ->setIntroduction($faker->paragraph(2, true))
                    ->setDescription($faker->paragraphs(6, true))
                    ->setCategory($category)
                    ->setFeatured($faker->boolean(10));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
