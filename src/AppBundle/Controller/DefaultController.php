<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
use AppBundle\Entity\Profile;
use AppBundle\Form\Type\InvoiceType;
use AppBundle\Form\Type\ProfileType;

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
     * @Route("/new-profile", name="new-profile")
     * @param Request $request
     * @return Response
     */
    public function newProfileAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        $profile = new Profile();
        $profile->setUser($user->getId());
        $form = $this->createForm($this->get('form_profile_type'), $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $this->addFlash('success', 'Profile has been added.');
            return $this->redirectToRoute('profiles');
        }

        return $this->render('default/new-profile.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/new-client", name="new-client")
     */
    public function newClientAction(Request $request)
    {
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

    /**
     * @Route("/invoice/{invoice}/item/{itemId}", name="edit-item")
     */
    public function itemEditAction(Request $request, $invoice, $itemId)
    {
        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('AppBundle:Item')->find($itemId);
        if (!$item) {
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
    }

    /**
     * @Route("/profile/edit/{id}", name="edit-profile")
     */
    public function itemProfileAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:Profile')->find($id);
        if (! $profile) {
            throw $this->createNotFoundException('No profile found for id ' . $id);
        }
        $form = $this->createForm($this->get('form_profile_type'), $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $this->addFlash(
                'success',
                'Profile has been changed.'
            );
        }

        return $this->render('default/edit-profile.html.twig', array(
            'form' => $form->createView(),
            'profile' => $profile
        ));
    }

    /**
     * @Route("/client/id/{id}", name="edit-client")
     */
    public function itemClientAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('AppBundle:Client')->find($id);
        if (! $client) {
            throw $this->createNotFoundException('No client found for id ' . $id);
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
     * @Route("/invoice/edit/{id}", name="edit-invoice")
     */
    public function invoiceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('AppBundle:Invoice')->find($id);
        if (! $invoice) {
            throw $this->createNotFoundException('No invoice found for id ' . $id);
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
     * @Route("/invoice/delete/{id}", name="delete-invoice")
     */
    public function deleteInvoiceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('AppBundle:Invoice')->find($id);
        if (! $invoice) {
            throw $this->createNotFoundException('No invoice found for id ' . $id);
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
     * @Route("/profile/delete/{id}", name="delete-profile")
     */
    public function deleteProfileAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $profile = $em->getRepository('AppBundle:Profile')->find($id);
        if (! $profile) {
            throw $this->createNotFoundException('No profile found for id ' . $id);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($profile);
        $em->flush();
        $this->addFlash(
            'success',
            'Profile has been Removed.'
        );

        return $this->redirectToRoute('profiles');
    }

    /**
     * @Route("/client/delete/{id}", name="delete-client")
     */
    public function deleteClientAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('AppBundle:Client')->find($id);
        if (! $client) {
            throw $this->createNotFoundException('No client found for id ' . $id);
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
     * @Route("/profiles", name="profiles")
     */
    public function profilesAction(Request $request)
    {
        $profiles = $this->getDoctrine()
            ->getRepository('AppBundle:Profile')
            ->findAll();

        return $this->render('default/profiles.html.twig', array(
            'profiles' => $profiles
        ));
    }

    /**
     * @Route("/invoice/id/{id}/items", name="invoice")
     */
    public function itemsAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($id);
        $items = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->findBy(
                array('invoice' => $id)
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
     * @Route("/invoice/preview/{id}", name="preview")
     */
    public function previewAction(Request $request, $id)
    {
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($id);
        $items = $this->getDoctrine()
            ->getRepository('AppBundle:Item')
            ->findBy(
                array('invoice' => $id)
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
     * @Route("/invoice/pdf/{id}", name="pdf")
     */
    public function pdfAction(Request $request, $id)
    {
        $pageUrl = $this->generateUrl('preview', array('id' => $id), true); // use absolute path!

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
