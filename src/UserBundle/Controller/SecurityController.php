<?php

namespace UserBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\RememberPasswordType;

class SecurityController extends Controller {   
    
    /**
     * @Route("/login",name="login")
     */
    public function loginAction(Request $request)
    {          
        $Session = $this->get('session');

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        $rememberPassword = $this->createForm(RememberPasswordType::class);
        
        $rememberPassword->handleRequest($request);
        if($request->isMethod('POST')){
            if ($rememberPassword->isSubmitted() && $rememberPassword->isValid()) {
                 $userEmail = $rememberPassword->get('email')->getData();
                 
                 $userManager = $this->get('user_manager');
                 
                 $userManager->sendResetPasswordLink($userEmail);
            }
        }

        return $this->render('UserBundle:Login:login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
        'rememberPassword' => $rememberPassword->createView()
         ));
    }
}
