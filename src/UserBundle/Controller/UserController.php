<?php

namespace UserBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\AccountSettingsType;
use UserBundle\Form\ChangePasswordType;
use UserBundle\Entity\User;


class UserController extends Controller {   
    
    /**
     * @Route("/account-settings",name="user_accountSettings")
     * @Template("UserBundle:User:accountSettings.html.twig")
     */
    public function accountSettingsAction(Request $request)
    {    
        $Session = $this->get('session');
         
        $User = $this->getUser();
       
        $accountSettginsForm = $this->createForm(AccountSettingsType::class, $User);

        if ($request->isMethod('POST') && $request->request->has('accountSettings')) {

            $accountSettginsForm->handleRequest($request);

            if ($accountSettginsForm->isSubmitted()) {

                
                
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($User);
                    $em->flush();
                    
       
                    $Session->getFlashBag()->add('success', 'Twoje dane zostały zaktualizowane');
                    return $this->redirect($this->generateUrl('user_accountSettings'));
                    
                } else {
                    
                    $Session->getFlashBag()->add('error', 'Wystąpił błąd. Twoje dane nie zostały zaktualizowane');
                    return $this->redirect($this->generateUrl('user_accountSettings'));
                }
            }
            
//            change password
            $changePasswdForm = $this->createForm(ChangePasswordType::class, $User);

            if ($request->isMethod('POST') && $request->request->has('changePassword')) {

            $changePasswdForm->handleRequest($request);

            if ($changePasswdForm->isSubmitted()) {
                
                if($changePasswdForm->isValid()){
                    try {

                        
                        $userManager = $this->get('user_manager');

                        $userManager->changePassword($User);

                        $Session->getFlashBag()->add('success', 'Twoje hasło zostało zmienione!');

                        return $this->redirect($this->generateUrl('user_accountSettings'));
                    } catch (UserException $exc) {
                        $Session->getFlashBag()->add('error', $exc->getMessage());
                    }
                } else {
                     $Session->getFlashBag()->add('error', 'Popraw błędy formularza!');
                }
                }
            }


            return array (
            'user' => $User,
            'accountSettginsForm' => $accountSettginsForm->createView(),
            'changePasswdForm' => $changePasswdForm->createView()
        );
    }
}
