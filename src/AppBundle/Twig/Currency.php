<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;

class Currency extends \Twig_Extension
{
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('currency', array($this, 'currencyFunction')),
        );
    }

    public function currencyFunction($currency) {
        return Intl::getCurrencyBundle()->getCurrencySymbol($currency);
    }

    public function getName() {
        return 'your_extension';
    }
}
