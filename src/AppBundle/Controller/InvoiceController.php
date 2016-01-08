<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Invoice;

class InvoiceController extends Controller
{
    /**
     * @Route("/invoice/new", name="new-invoice")
     * @Security("has_role('ROLE_USER')")
     */
    public function newInvoiceAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
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
            return $this->redirectToRoute('invoices');
        }

        return $this->render('default/new-invoice.html.twig', array(
            'form' => $form->createView(),
            'clients' => $clients
        ));
    }

    /**
     * @Route("/invoice/edit/{invoiceId}", name="edit-invoice")
     * @Security("has_role('ROLE_USER')")
     */
    public function invoiceAction(Request $request, $invoiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);
        if (! $invoice) {
            throw $this->createNotFoundException('No invoice found for id ' . $invoiceId);
        }
        $form = $this->createForm($this->get('form_invoice_type'), $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($invoice);
            $em->flush();
            $this->addFlash(
                'success',
                'Invoice has been changed.'
            );
        }

        return $this->render('default/edit-invoice.html.twig', array(
            'form' => $form->createView(),
            'invoice' => $invoice
        ));
    }

    /**
     * @Route("/invoice/delete/{invoiceId}", name="delete-invoice")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteInvoiceAction($invoiceId)
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);
        if (! $invoice) {
            throw $this->createNotFoundException('No invoice found for id ' . $invoiceId);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($invoice);
        $em->flush();
        $this->addFlash(
            'success',
            'Invoice has been Removed.'
        );

        return $this->redirectToRoute('invoices');
    }

    /**
     * @Route("/invoices", name="invoices")
     * @Security("has_role('ROLE_USER')")
     */
    public function invoicesAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profiles = $this->getDoctrine()
            ->getRepository('AppBundle:Profile')
            ->findBy(
                array('user' => $user)
            );
        $profileIds = array();
        foreach($profiles as $profile) {
            array_push($profileIds, $profile->getId());
        }
        if (! $profiles) {
            return $this->render('default/invoices.html.twig', array(
                'profiles' => null
            ));
        }
        $invoices = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->findBy(
                array('profile' => $profileIds)
            );

        return $this->render('default/invoices.html.twig', array(
            'invoices' => $invoices,
            'profiles' => $profiles
        ));
    }

    /**
     * @Route("/invoice/preview/{invoiceId}", name="preview")
     * @Security("has_role('ROLE_USER')")
     */
    public function previewAction(Request $request, $invoiceId)
    {
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoiceId);
        $items = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->findBy(
                array('invoice' => $invoiceId)
            );
        $client = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(
                array('id' => $invoice->getClient())
            );
        $profile = $this->getDoctrine()
            ->getRepository('AppBundle:Profile')
            ->findBy(
                array('id' => $invoice->getProfile())
            );

        return $this->render('default/preview.html.twig', array(
            'invoice' => $invoice,
            'items' => $items,
            'client' => $client[0],
            'profile' => $profile[0]
        ));
    }

    /**
     * @Route("/invoice/pdf/{invoiceId}", name="pdf")
     * @Security("has_role('ROLE_USER')")
     */
    public function pdfAction(Request $request, $invoiceId)
    {
        $pageUrl = $this->generateUrl('preview', array('id' => $invoiceId), true); // use absolute path!

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );
    }

    /**
     * Ajax work - commented out for now
     * @Route("/invoice/{invoice}/item/{itemId}", name="edit-item")
     */
    /*    public function editItemAction(Request $request, $invoice, $itemId)
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
        }*/
}