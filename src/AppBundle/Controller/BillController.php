<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bill;
use AppBundle\Form\BillType;
use AppBundle\Form\SelectPartnerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BillController extends Controller
{
    /**
     * @Route("/createBill", name="createbill")
     */
    public function addBillAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->render('error.html.twig', array(
                'error'=>'no user selected',
                'usersession'=>$session->get('user')
            ));
        }

        //todo delete
        //$form = $this->createForm(SelectPartnerType::class, array(), array(
        //    'partners'=>[
         //       $selected_user->getPartnerone(),
         //       $selected_user->getPartnertwo()
         //   ]));
        $bill = new Bill();
        $form = $this->createForm(BillType::class, $bill, array(
            'partners'=>[
                $selected_user->getPartnerone(),
                $selected_user->getPartnertwo()
            ]));

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            //todo: make this better (e.g. dont fetch the partner again from db)
            $em = $this->getDoctrine()->getManager();;
            $partner = $em->getRepository('AppBundle:Partner')->find($form['partner']['partner']->getData()->getId());
            if (!$partner) {
                throw $this->createNotFoundException(
                    'No partner found for id '.$partner
                );
            }
            $bill->setPartner($partner);
            $em->persist($bill);
            $em->flush();

            $this->addFlash('notice', 'Bill ' . $bill->getBillname() . ' Saved, ID: ' . $bill->getId());
            return $this->redirectToRoute('mainpage');
        }

        return $this->render('bill/createbill.html.twig', array(
            'usersession'=>$session->get('user'),
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/showBill", name="showbill")
     */
    public function showBillAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->render('error.html.twig', array(
                'error'=>'no user selected',
                'usersession'=>$session->get('user')
            ));
        }

        return $this->render('bill/showbills.html.twig', array(
            'usersession'=>$session->get('user')
        ));

    }

}