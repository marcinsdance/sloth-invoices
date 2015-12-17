<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Client;
use AppBundle\Form\Type\InvoiceType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/new-client", name="new-client")
     */
    public function newClientAction(Request $request)
    {
        $client = new Client();
        $form = $this->createForm($this->get('form_client_type'), $client);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $this->addFlash(
                'success',
                'Client has been added.'
            );
        }

        return $this->render('default/new-client.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/new-invoice", name="new-invoice")
     */
    public function newInvoiceAction(Request $request)
    {
        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findAll();

        $invoice = new Invoice();
        $form = $this->createForm($this->get('form_invoice_type'), $invoice);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($invoice);
            $em->flush();
            $this->addFlash(
                'success',
                'Invoice has been added.'
            );
        }

        return $this->render('default/new-invoice.html.twig', array(
            'form' => $form->createView(),
            'clients' => $clients
        ));
    }

    /**
     * @Route("/invoices", name="invoices")
     */
    public function invoicesAction(Request $request)
    {
        $invoices = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->findAll();

        return $this->render('default/invoices.html.twig', array(
            'invoices' => $invoices
        ));
    }

    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdfAction(Request $request)
    {
        $pageUrl = $this->generateUrl('homepage', array(), true); // use absolute path!

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }
}
