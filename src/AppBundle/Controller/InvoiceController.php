<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InvoiceController extends Controller
{
    /**
     * @Route("/showInvoices", name="showinvoices")
     */
    public function showInvoicesAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->render('error.html.twig', array(
                'error'=>'no user selected',
                'usersession'=>$session->get('user')
            ));
        }

        //todo: pull the invoices from the db


        return $this->render('invoices/invoices.html.twig', array(
            'usersession'=>$session->get('user')
        ));
    }

    /**
     * @Route("/createInvoice", name="createinvoice")
     */
    public function createInvoiceAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->render('error.html.twig', array(
                'error'=>'no user selected',
                'usersession'=>$session->get('user')
            ));
        }

        //todo: create the invoice...

        // if the form is handled
        //$this->addFlash('notice', 'Invoice created...');
        //return $this->redirectToRoute('showinvoices');

        return $this->render('invoices/createinvoice.html.twig', array(
            'usersession'=>$session->get('user')
        ));
    }
}
