<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\Item;
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
     * @Route("/new-item/invoice/{invoice}", name="new-item")
     */
    public function newItemAction(Request $request, $invoice)
    {
        $item = new Item();
        $invoiceObject = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoice);
        $item->setInvoice($invoiceObject);
        $form = $this->createForm($this->get('form_item_type'), $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            $this->addFlash(
                'success',
                'Item has been added.'
            );
        }

        return $this->render('default/new-item.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/invoice/{invoice}/item/{itemId}", name="edit-item")
     */
    public function editItemAction(Request $request, $invoice, $itemId)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($itemId);
        if (! $item) {
            throw $this->createNotFoundException('No item found for id ' . $itemId);
        }
        $invoiceObject = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoice);
        $item->setInvoice($invoiceObject);
        $form = $this->createForm($this->get('form_item_type'), $item, array(
            'method' => 'PUT'
        ));
        $form->handleRequest($request);

        $jsonResponse = new JsonResponse();
        if (!$form) {
            return $jsonResponse->setData(array(
                'message','false'
            ));
        }

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            return $jsonResponse->setData(array(
                'message', 'Item has been added.'
            ));
        }

        $errors = $this->getFormErrors($form);

        $stringifyErrors = implode(',',$errors);
        return $jsonResponse->setData(array(
            'message', 'Error, item has not been added: ' . $stringifyErrors
        ));
    }

    /**
     * @Route("/item/{itemId}/invoice/{invoice}", name="item-edit")
     */
    public function itemEditAction(Request $request, $invoice, $itemId)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($itemId);
        if (! $item) {
            throw $this->createNotFoundException('No item found for id ' . $itemId);
        }
        $invoiceObject = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoice);
        $item->setInvoice($invoiceObject);
        $form = $this->createForm($this->get('form_item_type'), $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();
            $this->addFlash(
                'success',
                'Item has been changed.'
            );
        }

        return $this->render('default/edit-item.html.twig', array(
            'form' => $form->createView(),
            'invoice' => $invoiceObject,
            'item' => $item
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
     * @Route("/clients", name="clients")
     */
    public function clientsAction(Request $request)
    {
        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findAll();

        return $this->render('default/clients.html.twig', array(
            'clients' => $clients
        ));
    }

    /**
     * @Route("/invoice/id/{id}", name="invoice")
     */
    public function invoiceAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($id);
        $items = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->findBy(
                array('invoice' => $id)
            );

        return $this->render('default/invoice.html.twig', array(
            'invoice' => $invoice,
            'items' => $items
        ));
    }

    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdfAction(Request $request)
    {
        $pageUrl = $this->generateUrl('invoices', array(), true); // use absolute path!

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }

    protected function getFormErrors($form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child /** @var Form $child */) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }
}
