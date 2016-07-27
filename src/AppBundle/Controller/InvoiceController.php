<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bill;
use AppBundle\Entity\Invoice;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        //get the unbilled bills from the db
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Bill');
        $query = $repository->createQueryBuilder('b')
            ->where('(b.partner = :p1 OR b.partner = :p2) AND b.billed = false')
            ->setParameter('p1', $selected_user->getPartnerone()->getId())
            ->setParameter('p2', $selected_user->getPartnertwo()->getId())
            ->orderBy('b.billdate', 'ASC')
            ->getQuery();
        $bills = $query->getResult();

        //calculate the amount for each partner
        $amount_p1 = $this->calculateAmount($bills, $selected_user->getPartnerone()->getId());
        $amount_p2 = $this->calculateAmount($bills, $selected_user->getPartnertwo()->getId());

        //todo: handle this without a form?
        $form = $this->createFormBuilder(array())
            ->add('Create the Invoice', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $bills !=null) {

            //create the invoice and update the bill
            $invoice = new Invoice();
            $invoice->setUser($em->getRepository('AppBundle:User')->find($selected_user->getId()));
            $invoice->setInvoicedate(new Datetime());
            if($amount_p1 < $amount_p2) {
                $invoice->setPayingpartner($selected_user->getPartnerone()->getPartnername());
            } else {
                $invoice->setPayingpartner($selected_user->getPartnertwo()->getPartnername());
            }
            $invoice->setAmount($this->calculatePayAmount($amount_p1, $amount_p2));

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

}
