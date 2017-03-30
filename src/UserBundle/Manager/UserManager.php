<?php

namespace UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as Templating;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use UserBundle\Mailer\UserMailer;
use UserBundle\Exception\UserException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UserBundle\Entity\User;

class UserManager {
    
    protected $doctrine;
    protected $router;
    protected $templating;
    protected $encoderFactory;
    protected $userMailer;
    
    function __construct(Doctrine $doctrine, Router $router, Templating $templating, EncoderFactory $encoderFactory, UserMailer $userMailer) {
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->templating = $templating;
        $this->encoderFactory = $encoderFactory;
        $this->userMailer = $userMailer;
        
    }
    
    protected function generateActionToken() {
       $result = substr(md5(uniqid(NULL, TRUE)), 0, 20);
        return $result;
    }
    
    protected function getRandomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

    public function sendResetPasswordLink($userEmail) {
        $User = $this->doctrine->getRepository('UserBundle:User')->findOneBy(array('email' => $userEmail));

        if ($User === null) {
            throw new UserException('Nie znaleziono takiego użytkownika');
        }
        
        $User->setActionToken($this->generateActionToken());

        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();

        $urlParams = array(
            'actionToken' => $User->getActionToken()
        );

        $resetUrl = $this->router->generate('user_resetPassword', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);

        $emailBody = $this->templating->render('UserBundle:Email:passwdResetLink.html.twig', array(
            'resetUrl' => $resetUrl
        ));

        $this->userMailer->send($User, 'Link resetujący hasło', $emailBody);
        
        return true;
    }
    
    public function resetPassword($actionToken) {
        
        $User = $this->doctrine->getRepository('UserBundle:User')
                ->findByActionToken($actionToken);
        
        if ($User === null) {
            throw new UserException('Podano błędne parametry akcji');
        }
        
        $plainPasswd = $this->getRandomPassword();
        $encoder = $this->encoderFactory->getEncoder($User);
        
        $encodedPasswd = $encoder->encodePassword($plainPasswd, $User->getSalt());
        
        $User->setPassword($encodedPasswd);
        $User->setActionToken(null);
        
        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        $emailBody = $this->templating->render('UserBundle:Email:newPassword.html.twig', array(
            'plainPasswd' => $plainPasswd
        ));
        
        $this->userMailer->send($User, 'Nowe hasło do konta', $emailBody);
        
        return true;
        
    }
    
    public function registerUser(User $User){
        
        
        if(null !== $User->getId()){
            throw new UserException('Taki użytkownik już istnieje!');
        }
        
        $encoder = $this->encoderFactory->getEncoder($User);
        $encodedPasswd = $encoder->encodePassword($User->getPlainPassword(), $User->getSalt());
        
        $User->setPassword($encodedPasswd);
        $User->setActionToken($this->generateActionToken());
        $User->setEnabled(false);
        
        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        $urlParams = array(
            'actionToken' => $User->getActionToken()
        );
        
        $activationUrl = $this->router->generate('user_activateAccount', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);
        
        $emailBody = $this->templating->render('UserBundle:Email:accountActivation.html.twig', array(
            'activationUrl' => $activationUrl
        ));
        
        $this->userMailer->send($User, 'Nowe konto utworzono - email rejestracyjny', $emailBody);
        
        return true;
    }
    
    public function activateAccount($actionToken){
        
          $User = $this->doctrine->getRepository('UserBundle:User')->findByActionToken($actionToken);
        
        if ($User === null) {
            throw new UserException('Podano błędne parametry akcji');
        }
        
        $User->setEnabled(true);
        $User->setActionToken(null);
        
        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        return true;
    }
    
    public function changePassword(User $User){
        
        if(null == $User->getPlainPassword()){
            throw new UserException('Nie ustawiono nowego hasła');
        }
        
        $encoder = $this->encoderFactory->getEncoder($User);
        $encoderPassword = $encoder->encodePassword($User->getPlainPassword(), $User->getSalt());
        $User->setPassword($encoderPassword);
        
        
        
        $em = $this->doctrine->getManager();
        $em->persist($User);
        $em->flush();
        
        return true;
        
    }

}
