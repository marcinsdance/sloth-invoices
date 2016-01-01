<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;

class Currency extends \Twig_Extension
{
    public function getFunctions() {
        return array(
            'currency' => new \Twig_Function_Method($this, 'currencyFunction'),
        );
    }

    public function currencyFunction($currency) {
        return Intl::getCurrencyBundle()->getCurrencySymbol($currency);
    }

    public function getName() {
        return 'your_extension';
    }
}