<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Bill;
use AppBundle\Entity\User;
use AppBundle\Form\BillType;
use AppBundle\Form\SelectPartnerType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BillController extends Controller
{
    /**
     * @Route("/createBill", name="createbill")
     */
    public function addBillAction(Request $request) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            return $this->renderErrorNoUserSelected();
        }

        $form = $this->createBillForm($selected_user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            //todo: make this better (e.g. dont fetch the partner again from db)
            $em = $this->getDoctrine()->getManager();
            $partner = $em->getRepository('AppBundle:Partner')->find($form['partner']['partner']->getData()->getId());
            if (!$partner) {
                throw $this->createNotFoundException(
                    'No partner found for id '.$partner
                );
            }

            $bill = $form->getData();
            $bill->setPartner($partner);
            $bill->setBilled(false);
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
            return $this->renderErrorNoUserSelected();
        }

        $bills = $this->getDoctrine()->getRepository('AppBundle:Bill')->findAllBillsFromTheUser($selected_user);

        return $this->render('bill/showbills.html.twig', array(
            'usersession'=>$session->get('user'),
            'bills'=> $bills
        ));
    }

    /**
     * @Route("/removeBill/{id}", name="removebill")
     */
    public function removeBillAction(Request $request, $id) {
        $session = $request->getSession();
        $selected_user = $session->get('user');

        if($selected_user == null) {
            throw $this->createAccessDeniedException('there is no user selected');
        }

        $em = $this->getDoctrine()->getManager();
        $bill = $em->getRepository('AppBundle:Bill')->findBillByIdFromUserNotBilled($selected_user, $id);

        if (!$bill) {
            throw $this->createNotFoundException('The bill is already billed ore you don\'t have a bill with the id: '.$id);
        }

        $em->remove($bill);
        $em->flush();

        return $this->redirectToRoute('showbill');
    }

    /**
     * Renders the error response if no user is selected.
     *
     * @return Response
     */
    private function renderErrorNoUserSelected() : Response {
        return $this->render('error.html.twig', array(
            'error'=>'no user selected',
            'usersession'=>null
        ));
    }

    /**
     * Create a bill form with a given user.
     *
     * @param User $user
     * @return Form
     */
    private function createBillForm(User $user) : Form {
        $bill = new Bill();
        $bill->setBilldate(new Datetime());
        $form = $this->createForm(BillType::class, $bill, array(
            'partners'=>[
                $user->getPartnerone(),
                $user->getPartnertwo()
            ]));
        return $form;
    }

}