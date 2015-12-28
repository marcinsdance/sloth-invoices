<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('Home', array('route' => 'homepage'));
        $menu->addChild('Invoices', array('route' => 'invoices'));
        $menu->addChild('Clients');
        $menu['Invoices']->addChild('New Invoice', array('route' => 'new-invoice'));
        $menu['Clients']->addChild('New Client', array('route' => 'new-client'));
        return $menu;
    }
}