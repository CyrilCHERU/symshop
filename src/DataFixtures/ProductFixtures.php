<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Liior\Faker\Prices;
use Bezhanov\Faker\Provider\Commerce;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends BaseFixtures implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }


    protected function loadData(ObjectManager $manager)
    {

        for ($j = 0; $j < 50; $j++) {

            $product = new Product;

            $product->setTitle($this->faker->productName)
                ->setPrice($this->faker->price(20, 500) * 100)
                ->setImage($this->faker->imageUrl(100, 100))
                ->setIntroduction($this->faker->paragraph(2, true))
                ->setDescription($this->faker->paragraphs(6, true))
                ->setCategory($this->getReference('category_' . mt_rand(0, 4)))
                ->setFeatured($this->faker->boolean(10));

            $this->addReference('product_' . $j, $product);

            $manager->persist($product);
        }
    }
}
