<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Client;

class ClientController extends Controller
{
    /**
     * @Route("/client/new", name="new-client")
     * @Security("has_role('ROLE_USER')")
     */
    public function newClientAction(Request $request)
    {
        $profiles = $this->get('voicein_helper')->getProfiles();
        $clients = $this->get('voicein_helper')->getClients();

        if (!$profiles) {
            $this->addFlash(
                'notice',
                'You\'ve been redirected to New Profile page. Please add a profile before adding a client.'
            );
            return $this->redirectToRoute('new-profile');
        }

        $user = $this->container->get('security.context')->getToken()->getUser();
        $client = new Client();
        $client->setUser($user->getId());
        $form = $this->createForm($this->get('form_client_type'), $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $this->addFlash('success', 'Client has been added.');
            return $this->redirectToRoute('clients');
        }

        return $this->render('default/new-client.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/client/edit/{clientId}", name="edit-client")
     * @Security("has_role('ROLE_USER')")
     */
    public function editClientAction(Request $request, $clientId)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('AppBundle:Client')->find($clientId);
        if (! $client) {
            throw $this->createNotFoundException('No client found for id ' . $clientId);
        }
        $form = $this->createForm($this->get('form_client_type'), $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $this->addFlash(
                'success',
                'Client has been changed.'
            );
        }

        return $this->render('default/edit-client.html.twig', array(
            'form' => $form->createView(),
            'client' => $client
        ));
    }

    /**
     * @Route("/client/delete/{clientId}", name="delete-client")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteClientAction($clientId)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('AppBundle:Client')->find($clientId);
        if (! $client) {
            throw $this->createNotFoundException('No client found for id ' . $clientId);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($client);
        $em->flush();
        $this->addFlash(
            'success',
            'Client has been Removed.'
        );

        return $this->redirectToRoute('clients');
    }

    /**
     * @Route("/clients", name="clients")
     * @Security("has_role('ROLE_USER')")
     */
    public function clientsAction(Request $request)
    {
        $clients = $this->get('voicein_helper')->getClients();
        $profiles = $this->get('voicein_helper')->getProfiles();

        return $this->render('default/clients.html.twig', array(
            'clients' => $clients,
            'profiles' => $profiles
        ));
    }
}