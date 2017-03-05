<?php

namespace UserBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as Templating;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use UserBundle\Mailer\UserMailer;
use UserBundle\Exception\UserException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        return substr(md5(uniqid(NULL, TRUE)), 0, 20);
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
        $User = $this->doctrine->getRepository('UserBundle:User')
                ->findByEmail($userEmail);

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

        $this->userMailer->send($User, 'Link resetujący hasło', $htmlBody);
        
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

}
