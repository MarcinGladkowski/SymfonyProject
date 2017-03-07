<?php

namespace UserBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use UserBundle\Form\RememberPasswordType;
use UserBundle\Exception\UserException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

use UserBundle\Form\RegisterType;
use UserBundle\Entity\User;


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
        
        $rememberPassword = $this->createForm(RememberPasswordType::class, array(
            'action' => $this->generateUrl('login'),
            ));
        
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
    
     /**
     * @Route("/reset-password",name="reset_password")
     */
    public function resetAction(Request $request){
       
        $Session = $this->get('session');
        
        $rememberPassword = $this->createForm(RememberPasswordType::class, array(
            'action' => $this->generateUrl('login'),
            ));
        
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

        return $this->render('UserBundle:Login:reset.html.twig', array(
        'rememberPassword' => $rememberPassword->createView()
         ));
    } 
    
     /**
     * @Route("/register",name="register")
     * @Template("UserBundle:Register:register.html.twig")
     */
    public function registerAction(Request $request)
    {
        $User = new User();
        $registerForm = $this->createForm(RegisterType::class, $User);
            
        if($request->isMethod('POST')){
            $registerForm->handleRequest($request);
            if($registerForm->isValid()){
                try {
                    
                    $Session = $this->get('session');

                    $userManager = $this->get('user_manager');

                    $userManager->registerUser($userEmail);
                    
                    $Session->getFlashBag()->add('success', 'Konto zostało założone!');
                    
                    return $this->redirect($this->generateUrl('login'));
                    
                } catch (UserException $exc) {
                    $Session->getFlashBag()->add('error', $exc->getMessage());
                }
            }
        }
        
        return array(
            'registerForm' => $registerForm->createView()
        );
    } 
    
    /**
     * @Route("/account-activation/{actionToken}",name="user_activateAccount")
     */
    public function activateAccountAction($actionToken)
    {
        try{
            
            $userManager = $this->get('user_manager');
            $userManager->activateAccount($actionToken);
            
            $Session->getFlashBag()->add('success', 'Twoje konto zostalo aktywowane!');
            
        } catch(UserException $ex)
        {
            
            $Session->getFlashBag()->add('error', $ex->getMessage());

        }

        return $this->redirect($this->generateUrl('login'));
    }
}
