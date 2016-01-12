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
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Invoices', array('route' => 'invoices'))
            ->setAttribute('class','nav-link nav-item')
            ->setLinkAttribute('data-hover','Invoices');
        $menu->addChild('Clients', array('route' => 'clients'))
            ->setAttribute('class','nav-link nav-item')
            ->setLinkAttribute('data-hover','Clients');
        $menu->addChild('Profiles', array('route' => 'profiles'))
            ->setAttribute('class','nav-link nav-item')
            ->setLinkAttribute('data-hover','Profiles');
        return $menu;
    }
}