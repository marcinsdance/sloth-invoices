<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Item;

class ItemController extends Controller
{
    /**
     * @Route("/invoice/{invoiceId}/item/new", name="new-item")
     * @Security("has_role('ROLE_USER')")
     */
    public function newItemAction(Request $request, $invoiceId)
    {
        $item = new Item();
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoiceId);
        $item->setInvoice($invoice);
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
            return $this->redirectToRoute('invoice',array('invoiceId' => $invoiceId));
        }

        return $this->render('default/new-item.html.twig', array(
            'form' => $form->createView(),
            'invoice' => $invoice,
        ));
    }

    /**
     * @Route("/invoice/{invoiceId}/item/edit/{itemId}", name="edit-item")
     * @Security("has_role('ROLE_USER')")
     */
    public function itemEditAction(Request $request, $invoiceId, $itemId)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($itemId);
        if (!$item) {
            throw $this->createNotFoundException('No item found for id ' . $itemId);
        }
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoiceId);
        $item->setInvoice($invoice);
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
            'invoice' => $invoice,
            'item' => $item
        ));
    }


    /**
     * @Route("/invoice/{invoiceId}/items", name="invoice")
     * @Security("has_role('ROLE_USER')")
     */
    public function itemsAction(Request $request, $invoiceId)
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

        return $this->render('default/items.html.twig', array(
            'invoice' => $invoice,
            'items' => $items,
            'client' => $client[0]
        ));
    }

    /**
     * @Route("/invoice/{invoiceId}/item/delete/{itemId}", name="delete-item")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteItemAction($invoiceId, $itemId)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($itemId);
        if (! $item) {
            throw $this->createNotFoundException('No item found for id ' . $itemId);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();
        $this->addFlash(
            'success',
            'Item has been Removed.'
        );

        return $this->redirectToRoute('invoice',array('invoiceId' => $invoiceId));
    }
}