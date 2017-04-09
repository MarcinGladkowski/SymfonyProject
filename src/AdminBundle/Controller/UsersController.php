<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\VarDumper\VarDumper;


class UsersController extends Controller
{
    /**
     * @Route(
     *      "/list", 
     *      name="admin_users"
     * )
     * 
     * @Template()
     */
    public function indexAction()
    {   
        $usersRepository = $this->getDoctrine()->getRepository('UserBundle:User');
        
        $users = $usersRepository->findAll();
        
        
        return array(
            'users' => $users
        );
    }
}
