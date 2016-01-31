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
        $profiles = $this->get('voicein_helper')->getProfiles();
        $clients = $this->get('voicein_helper')->getClients();

        if (!$profiles) {
            $this->addFlash(
                'notice',
                'You\'ve been redirected to New Profile page. Please add a profile before adding an invoice.'
            );
            return $this->redirectToRoute('new-profile');
        }

        if (!$clients) {
            $this->addFlash(
                'notice',
                'You\'ve been redirected to New Client page. Please add a client before adding an invoice.'
            );
            return $this->redirectToRoute('new-client');
        }

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
            'clients' => $clients,
            'profiles' => $profiles
        ));
    }

    /**
     * @Route("/invoice/{invoiceId}/edit", name="edit-invoice")
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
     * @Route("/invoice/{invoiceId}/delete", name="delete-invoice")
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
        $clients = $this->get('voicein_helper')->getClients();
        $profiles = $this->get('voicein_helper')->getProfiles();
        $invoices = $this->get('voicein_helper')->getInvoices();

        return $this->render('default/invoices.html.twig', array(
            'invoices' => $invoices,
            'profiles' => $profiles,
            'clients' => $clients
        ));
    }

    /**
     * @Route("/invoice/{invoiceId}/preview", name="preview")
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
     * @Route("/invoice/{invoiceId}/pdf", name="pdf")
     * @Security("has_role('ROLE_USER')")
     */
    public function pdfAction(Request $request, $invoiceId)
    {
        $pageUrl = $this->generateUrl('preview', array('invoiceId' => $invoiceId), true);
        $session = $this->get('session');
        $session->save();
        session_write_close();
        $invoice = $this->getDoctrine()
            ->getRepository('AppBundle:Invoice')
            ->find($invoiceId);
        $clientArray = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy(
                array('id' => $invoice->getClient())
            );
        $profileArray = $this->getDoctrine()
            ->getRepository('AppBundle:Profile')
            ->findBy(
                array('id' => $invoice->getProfile())
            );
        $client = $clientArray[0];
        $profile = $profileArray[0];
        $pdf = $this->get("white_october.tcpdf")->create();
//        $pdf->setDate($invoice->getDate);
//        $pdf->setInvoiceNumber($invoice->getNumber);
//        $pdf->setCustomerId('5');
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($profile->getContactName());
        $pdf->SetTitle($profile->getCompanyName());
        $pdf->SetSubject('(Invoice');
        $pdf->SetKeywords('Invoice, PDF');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//        $pdf->setLanguageArray($l);
        $pdf->AddPage();
        $pdf->SetFillColor(109, 119, 144);
// To
        $pdf->setCellPaddings(1, 0.2, 0, 0);
        $pdf->setCellMargins(0, 1, 1, 1);
        $pdf->SetTextColor(255, 255, 255);
//        $pdf->SetFont($headerfont[0], 'N', $headerfont[2] + 10);
        $pdf->MultiCell(55, 5, 'To:', 0, 'L', 1, 0, '', '', true);
        $pdf->SetTextColor(46, 56, 81);
        $pdf->MultiCell(100, 5, $client->getContactName(), 0, 'L', 0, 1, 14, 58, true);
        $pdf->MultiCell(100, 5, $client->getContactName(), 0, 'L', 0, 1, 14, 63, true);
// header
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->setCellPaddings(1, 1, 0, 1);
        $pdf->MultiCell(45, 5, 'Developer', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(45, 5, 'Job', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(45, 5, 'Payment Terms', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(45, 5, 'Due Date', 0, 'L', 1, 1, '', '', true);
        $pdf->SetTextColor(46, 56, 81);
        $pdf->setCellMargins(0, 1, 1, 1);
        $pdf->MultiCell(44, 5, $profile->getContactName(), 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(44, 5, 'Project Manager', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(44, 5, 'Due upon receipt', 0, 'L', 0, 0, '', '', true);
        $pdf->MultiCell(44, 5, $profile->getContactName() . "\n"."\n", 0, 'L', 0, 1, '', '', true);
// listing
        $pdf->SetTextColor(255, 255, 255);
        $pdf->setCellMargins(0, 0, 0, 0);
        $pdf->MultiCell(15, 5, 'Qty', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(115, 5, 'Description', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(25, 5, 'Unit Price', 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell(25, 5, 'Line Total', 0, 'C', 1, 1, '', '', true);
        $pdf->SetTextColor(46, 56, 81);
        $pdf->setCellMargins(0, 0.2, 0, 0);
        $pdf->Output('filename.pdf');
        return new Response();

/*        return new Response(
            $this->get('knp_snappy.pdf')->getOutput(
                $pageUrl,
                array('cookie' => array($session->getName() => $session->getId()))
            ),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="file.pdf"'
            )
        );*/
    }

    /**
     * @Route("/invoice/{invoiceId}/email", name="email")
     * @Security("has_role('ROLE_USER')")
     */
    public function emailAction(Request $request, $invoiceId)
    {
        $pageUrl = $this->generateUrl('preview', array('invoiceId' => $invoiceId), true);
        $session = $this->get('session');
        $session->save();
        session_write_close();
        $em = $this->getDoctrine()->getManager();
        $invoice = $em->getRepository('AppBundle:Invoice')->find($invoiceId);
        if (! $invoice) {
            throw $this->createNotFoundException('No invoice found for id ' . $invoiceId);
        }
        $form = $this->createForm($this->get('form_email_type'), array(), array('invoice_id' => $invoice->getNumber()));
        $form->handleRequest($request);
/*        $this->get('knp_snappy.pdf')->generate(
            $pageUrl,
            '/tmp/invoice_' . $invoice->getNumber() . '.pdf',
            array('cookie' => array($session->getName() => $session->getId())),
            true);*/

        if ($form->isSubmitted() && $form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject($form->get('subject')->getData())
                ->setFrom($form->get('emailfrom')->getData())
                ->setTo($form->get('emailto')->getData())
                ->setBody(
                    $this->renderView(
                        'default/mail.html.twig',
                        array(
                            'ip' => $request->getClientIp(),
                            'message' => $form->get('message')->getData()
                        )
                    )
                )
            ->attach(\Swift_Attachment::fromPath('/tmp/invoice_' . $invoice->getNumber() . '.pdf'));

            if ($this->get('mailer')->send($message)) {
                $this->addFlash(
                    'success',
                    'Email has been sent.'
                );
            } else {
                $this->addFlash(
                    'error',
                    'Error occured'
                );
            }

        }

        return $this->render('default/email-invoice.html.twig', array(
            'form' => $form->createView(),
            'invoice' => $invoice
        ));
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