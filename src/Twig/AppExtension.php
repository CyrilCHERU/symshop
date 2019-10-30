<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice'], [])
        ];
    }

    public function formatPrice($content): string
    {

        $price = $content / 100;
        $price = number_format($price, 2, ",", " ") . " €";

        return $price;
    }
}
