<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Partner;
use AppBundle\Entity\User;
use AppBundle\Form\SelectUserType;
use AppBundle\Form\UserType;
use AppBundle\Persistence\UserPersistence;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $user = $this->createNewUserWithPartners();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $this->saveUserToDatabase($user);
            $this->addFlash('notice', 'User ' . $user->getUsername() . ' Saved, ID: ' . $user->getId());

            return $this->redirectToRoute('mainpage');
        }

        return $this->render('user/newuser.html.twig', array(
            'form'=> $form->createView(),
            'usersession'=>$session->get('user')
        ));
    }

    /**
     * @Route("/loginUser", name="loginuser")
     */
    public function loginUserAction(Request $request) {
        $session = $request->getSession();
        $form = $this->createForm(SelectUserType::class, array());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //set the session for the selected user
            $selected_user = $form['users']->getData();
            $session->set('user', $selected_user);
        }

        return $this->render('user/login.html.twig', array(
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

        return $this->redirectToRoute('loginuser');
    }

    /**
     * Create a new and empty user with two empty partners
     *
     * @return User
     */
    private function createNewUserWithPartners() : User {
        $user = new User();
        $partnerone = new Partner();
        $partnertwo = new Partner();
        $user->setPartnerone($partnerone);
        $user->setPartnertwo($partnertwo);

        return $user;
    }

    /**
     * Save a given user to the database
     *
     * @param User $user
     */
    private function saveUserToDatabase(User $user) {
        $em = $this->getDoctrine()->getManager();
        $userPersistence = new UserPersistence($em);
        $userPersistence->persistUser($user);
    }
}