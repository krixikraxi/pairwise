<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Invoice;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    /**
     * @Route("/showInvoices", name="showinvoices")
     */
    public function showInvoicesAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->renderErrorNoUserSelected();
        }

        $invoices = $this->getDoctrine()->getRepository('AppBundle:Invoice')->findAllInvoicesFromTheUser($selected_user);

        return $this->render('invoices/invoices.html.twig', array(
            'usersession'=>$session->get('user'),
            'invoices'=>$invoices
        ));
    }

    /**
     * @Route("/createInvoice", name="createinvoice")
     */
    public function createInvoiceAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->renderErrorNoUserSelected();
        }

        $em = $this->getDoctrine()->getManager();
        $bills = $em->getRepository('AppBundle:Bill')->findAllNotBilledBillsFromTheUser($selected_user);

        //calculate the amount for each partner
        $amount_p1 = $this->calculateAmount($bills, $selected_user->getPartnerone()->getId());
        $amount_p2 = $this->calculateAmount($bills, $selected_user->getPartnertwo()->getId());

        $form = $this->createFormBuilder(array())
            ->add('Create the Invoice', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $bills != null) {

            $invoice = $this->createNewInvoice($bills, $selected_user, $em);

            /** @var Bill $bill */
            foreach ($bills as $bill) {
                $bill->setBilled(true);
                $bill->setInvoice($invoice);
            }

            $em->persist($invoice);
            $em->flush();

            $this->addFlash('notice', 'Invoice created...');
            return $this->redirectToRoute('showinvoices');
        }

        return $this->render('invoices/createinvoice.html.twig', array(
            'usersession'=>$session->get('user'),
            'bills'=>$bills,
            'amountp1'=>$amount_p1,
            'amountp2'=>$amount_p2,
            'form'=>$form->createView()
        ));
    }

    /**
     * Creates a new Invoice for a given User and Bills.
     *
     * @param array $bills
     * @param User $user
     * @param EntityManager $em
     * @return Invoice
     */
    private function createNewInvoice(array $bills, User $user, EntityManager $em) : Invoice {
        $invoice = new Invoice();
        $invoice->setUser($em->getRepository('AppBundle:User')->find($user->getId()));
        $invoice->setInvoicedate(new Datetime());

        $bill_sum_p1 = $this->calculateAmount($bills, $user->getPartnerone()->getId());
        $bill_sum_p2 = $this->calculateAmount($bills, $user->getPartnertwo()->getId());

        if($bill_sum_p1 < $bill_sum_p2) {
            $invoice->setPayingpartner($user->getPartnerone()->getPartnername());
        } else {
            $invoice->setPayingpartner($user->getPartnertwo()->getPartnername());
        }
        $invoice->setAmount($this->calculatePayAmount($bill_sum_p1, $bill_sum_p2));

        return $invoice;
    }

    /**
     * Calculates the bills amount for a given partner.
     *
     * @param $bills
     * @return int|mixed
     */
    private function calculateAmount($bills, $partnerid) {
        $amount = 0;

        /** @var Bill $bill */
        foreach ($bills as $bill) {
            if($bill->getPartner()->getId() == $partnerid) {
                $amount += $bill->getAmount();
            }
        }
        return $amount;
    }

    /**
     * Calculates the amount that has to be payed.
     *
     * @param $amount1
     * @param $amount2
     * @return float
     */
    private function calculatePayAmount($amount1, $amount2) {
        $endresult = 0;
        if($amount1 > $amount2) {
            $endresult = $amount1 - $amount2;
        } else {
            $endresult = $amount2 - $amount1;
        }
        return $endresult / 2;
    }

    /**
     * Renders the error response if no user is selected.
     *s
     * @return Response
     */
    private function renderErrorNoUserSelected() : Response {
        return $this->render('error.html.twig', array(
            'error'=>'no user selected',
            'usersession'=>null
        ));
    }

}
