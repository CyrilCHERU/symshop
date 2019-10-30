<?php

namespace App\DataFixtures;

use Faker\Generator;
use App\Entity\OrderInfo;
use App\Entity\OrderItem;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ProductFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixtures extends BaseFixtures implements DependentFixtureInterface
{

    /**
     * @var Generator
     *
     * @var [type]
     */
    protected $faker;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        $status = [
            OrderInfo::STATUS_PAYMENT_WAITING,
            OrderInfo::STATUS_PAYMENT_VALIDATED,
            OrderInfo::STATUS_SHIPPED
        ];

        for ($o = 0; $o < 40; $o++) {
            // on cree une entité OrderInfo
            // dépend d'un User
            $user = $this->getReference('user_' . mt_rand(0, 9));

            $order = new OrderInfo;

            $order->setAddress1($this->faker->streetAddress)
                ->setCity($this->faker->city)
                ->setZipCode($this->faker->countryCode)
                ->setStatus($this->faker->randomElement($status))
                ->setFullName($this->faker->name())
                ->setUser($user);

            $manager->persist($order);

            // On cree plusieurs OrderItems
            // depend de Product
            for ($i = 0; $i < mt_rand(1, 4); $i++) {
                $product = $this->getReference('product_' . mt_rand(0, 49));

                $item = new OrderItem;

                $item->setOrderInfo($order)
                    ->setProduct($product)
                    ->setPrice($product->getPrice())
                    ->setQuantity(mt_rand(1, 5));

                $manager->persist($item);
            }
        }
    }
}
