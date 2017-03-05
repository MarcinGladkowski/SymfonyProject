<?php

namespace UserBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\RememberPasswordType;
use UserBundle\Exception\UserException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

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
                
                try {
                    $userEmail = $rememberPassword->get('email')->getData();

                    $userManager = $this->get('user_manager');

                    $userManager->sendResetPasswordLink($userEmail);
                    
                    $Session->getFlashBag()->add('success', 'Zostało wysłane!');
                    
                    return $this->redirect($this->generateUrl('login'));
                    
                } catch (UserException $exc) {
                    $error = new FormError($exc->getMessage());
                    $rememberPassword->get('email')->addError($error);
                }
               
            }
        }

        return $this->render('UserBundle:Login:login.html.twig', array(
        'last_username' => $lastUsername,
        'error'         => $error,
        'rememberPassword' => $rememberPassword->createView()
         ));
    }
    
    /**
     * @Route("/reset-password/{actionToken}",name="user_resetPassword")
     */
    public function resetPasswordAction($actionToken)
    {
        try{
            
            $userManager = $this->get('user_manager');
            $userManager->resetPassword($actionToken);
            
            $Session->getFlashBag()->add('success', 'Powiadomienie zostało wysłane!');
            
        } catch(Exception $ex)
        {
            
            $Session->getFlashBag()->add('error', $ex->getMessage());

        }

        return $this->redirect($this->generateUrl('login'));
    }
}
