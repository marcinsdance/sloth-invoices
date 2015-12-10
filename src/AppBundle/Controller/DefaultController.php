<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Invoice;
use Doctrine\DBAL\Types\IntegerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


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
        $form = $this->createFormBuilder($invoice)
            ->add('number', TextType::class)
            ->add('date', DateTimeType::class)
            ->add('client', IntegerType::class)
            ->add('submit', SubmitType::class, array('label' => 'Add Invoice'))
            ->getForm();

        return $this->render('default/new.html.twig', array(
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
