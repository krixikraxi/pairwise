<?php

namespace AppBundle\Controller;

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

        //todo change
        $form = $this->createForm(SelectPartnerType::class, array(), array(
            'partners'=>[
                $selected_user->getPartnerone(),
                $selected_user->getPartnertwo()
            ]));

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