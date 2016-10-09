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


        return $this->render('default/index.html.twig', array(
            'usersession'=>$session->get('user')
        ));
    }


}
