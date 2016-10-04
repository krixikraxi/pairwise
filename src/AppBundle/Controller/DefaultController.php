<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\SelectUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="mainpage")
     */
    public function indexAction(Request $request) {
        $session = $request->getSession();
        $form = $this->createForm(SelectUserType::class, array());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //set the session for the selected user
            $selected_user = $form['users']->getData();
            $session->set('user', $selected_user);
        }

        return $this->render('default/index.html.twig', array(
            'form'=> $form->createView(),
            'usersession'=>$session->get('user')
        ));
    }


}
