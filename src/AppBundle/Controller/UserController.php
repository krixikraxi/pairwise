<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/createUser", name="createuser")
     */
    public function createUserAction(Request $request) {
        $session = $request->getSession();

        // create a new User
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'User hinzufügen'))
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            //save the user into the db
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'User ' . $user->getUsername() . ' Saved, ID: ' . $user->getId());
            return $this->redirectToRoute('mainpage');
        }

        return $this->render('user/newuser.html.twig', array(
            'form'=> $form->createView(),
            'usersession'=>$session->get('user')
        ));
    }

    /**
     * @Route("/logoutUser", name="logoutuser")
     */
    public function logoutUserAction(Request $request) {
        $session = $request->getSession();
        $session->remove('user');

        return $this->redirectToRoute('mainpage');


    }

}