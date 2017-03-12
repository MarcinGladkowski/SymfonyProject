<?php

namespace UserBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\AccountSettingsType;
use UserBundle\Entity\User;


class UserController extends Controller {   
    
    /**
     * @Route("/account-settings",name="user_accountSettgins")
     * @Template("UserBundle:User:accountSettings.html.twig")
     */
    public function accountSettingsAction(Request $request)
    {    
        $Session = $this->get('session');
         
        $User = $this->getUser();
       
        $accountSettginsForm = $this->createForm(AccountSettingsType::class, $User);

        if ($request->isMethod('POST')) {

            $accountSettginsForm->handleRequest($request);

            if ($accountSettginsForm->isSubmitted()) {


                if ($accountSettginsForm->isValid()) {

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($User);
                    $em->flush();

                    $Session->getFlashBag()->add('success', 'Twoje dane zostały zaktualizowane');
                    return $this->redirect($this->generateUrl('user_accountSettgins'));
                    
                } else {
                    
                    $Session->getFlashBag()->add('error', 'Wystąpił błąd. Twoje dane nie zostały zaktualizowane');
                    return $this->redirect($this->generateUrl('user_accountSettgins'));
                }
            }
        }


        return array (
            'accountSettginsForm' => $accountSettginsForm->createView()
        );
    }
}
