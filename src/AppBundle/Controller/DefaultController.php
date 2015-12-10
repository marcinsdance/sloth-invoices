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
     * @Route("/new-invoice", name="new-invoice")
     */
    public function newInvoiceAction(Request $request)
    {
        $invoice = new Invoice();
        $form = $this->createForm($this->get('form_invoice_type'), $invoice);

        return $this->render('default/new-invoice.html.twig', array(
            'form' => $form->createView()
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
