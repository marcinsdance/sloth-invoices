<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;

class Helper
{
    private $container;
    private $entityManager;
    private $user;

    public function __construct(Container $container, EntityManager $entityManager) {
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->user = $this->container->get('security.context')->getToken()->getUser();
    }

    /**
     * @return array
     */
    public function getClients()
    {
        $clients = $this->entityManager
            ->getRepository('AppBundle:Client')
            ->findBy(
                array('user' => $this->user)
            );

        return $clients;
    }

    /**
     * @return array
     */
    public function getProfiles()
    {
        $profiles = $this->entityManager
            ->getRepository('AppBundle:Profile')
            ->findBy(
                array('user' => $this->user)
            );

        return $profiles;
    }

    /**
     * @return array
     */
    public function getInvoices()
    {
        $profiles = $this->getProfiles();
        $profileIds = array();
        foreach($profiles as $profile) {
            array_push($profileIds, $profile->getId());
        }

        $invoices = $this->entityManager
            ->getRepository('AppBundle:Invoice')
            ->findBy(
                array('profile' => $profileIds)
            );

        return $invoices;
    }
}