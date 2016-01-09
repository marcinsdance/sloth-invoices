<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;

class Helper
{
    private $container;
    private $entityManager;

    public function __construct(Container $container, EntityManager $entityManager) {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function getClients()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $clients = $this->entityManager
            ->getRepository('AppBundle:Client')
            ->findBy(
                array('user' => $user)
            );

        return $clients;
    }
}