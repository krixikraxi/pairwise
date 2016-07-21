<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="mainpage")
     */
    public function indexAction(Request $request) {

        $repository = $this->getDoctrine()->getRepository('AppBundle:User');

        $users = $repository->findAll();

        if (!$users) {
            throw $this->createNotFoundException('No users found');
        }

        return $this->render('default/index.html.twig', array('users' => $users));
    }


}
