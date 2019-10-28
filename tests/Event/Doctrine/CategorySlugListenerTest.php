<?php

namespace App\Test\Event\Doctrine;

use App\Entity\Category;
use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;
use App\Event\Doctrine\CategorySlugListener;

class CategorySlugListenerTest extends TestCase
{
    public function testSlugIsGeneratedSuccessfully()
    {
        $category = new Category;
        $category->setTitle("salut tout le monde");

        $slugify = $this->createMock(Slugify::class);
        $slugify->method('slugify')->willReturn("salut-tout-le-monde");

        $listener = new CategorySlugListener($slugify);

        $listener->prePersist($category);

        $this->assertEquals("salut-tout-le-monde", $category->getSlug());
    }
}
