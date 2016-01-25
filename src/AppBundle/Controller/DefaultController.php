<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $profiles = $this->get('voicein_helper')->getProfiles();
        $clients = $this->get('voicein_helper')->getClients();
        $invoices = $this->get('voicein_helper')->getInvoices();
        if(count($profiles) > 0 && count($clients) > 0) {
            return $this->redirectToRoute('invoices');
        }

        return $this->render('default/index.html.twig', array(
            'profiles' => $profiles,
            'invoices' => $invoices,
            'clients' => $clients
        ));
    }

    /**
     * @Route("terms-and-conditions", name="tac")
     */
    public function tacAction(Request $request)
    {
        return $this->render('default/tac.html.twig');
    }
}
