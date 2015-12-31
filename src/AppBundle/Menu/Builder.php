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
        $menu->addChild('Invoices')
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        $menu->addChild('Clients')
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        $menu->addChild('Profiles')
            ->setAttribute('class','nav-item dropdown')
            ->setLabelAttribute('class','dropdown-toggle')
            ->setLabelAttribute('data-toggle','dropdown')
            ->setChildrenAttribute('class','dropdown-menu');
        $menu['Invoices']->addChild('All Invoices', array('route' => 'invoices'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        $menu['Invoices']->addChild('New Invoice', array('route' => 'new-invoice'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        $menu['Clients']->addChild('All Clients', array('route' => 'clients'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        $menu['Clients']->addChild('New Client', array('route' => 'new-client'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        $menu['Profiles']->addChild('All Profiles', array('route' => 'profiles'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        $menu['Profiles']->addChild('New Profile', array('route' => 'new-profile'))
            ->setAttribute('class','dropdown-item')
            ->setLinkAttribute('class','nav-link');
        return $menu;
    }
}