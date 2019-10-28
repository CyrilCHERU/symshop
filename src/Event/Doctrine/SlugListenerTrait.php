<?php

namespace App\Event\Doctrine;

use Cocur\Slugify\Slugify;


trait SlugListenerTrait
{

    public function __construct(Slugify $slugify)
    {
        $this->slugger = $slugify;
    }

    public function prePersist($entity)
    {

        if (empty($entity->getSlug())) {

            $entity->setSlug($this->slugger->slugify($entity->getTitle()));
        }
    }
}
