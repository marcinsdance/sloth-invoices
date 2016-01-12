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
        $menu->setChildrenAttribute('class','nav');
        $menu->addChild('Invoices', array('route' => 'invoices'))
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        $menu->addChild('Clients', array('route' => 'clients'))
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        $menu->addChild('Profiles', array('route' => 'profiles'))
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        return $menu;
    }
}