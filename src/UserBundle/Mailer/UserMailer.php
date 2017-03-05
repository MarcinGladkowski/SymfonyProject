<?php

namespace UserBundle\Mailer;

use UserBundle\Entity\User;

/**
 * Description of UserMailer
 *
 * @author Marcin<Marcin>
 */
class UserMailer {
    
    private $swiftMailer;
    private $fromEmail;
    private $fromName;
    
    function __construct(\Swift_Mailer $swiftMailer, $fromEmail, $fromName) {
        $this->swiftMailer = $swiftMailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
    }
    
    public function send(User $User, $subject, $htmlBody){
        
        $message = \Swift_Message::newInstance()
                  ->setSubject($subject)
                  ->setFrom($this->fromEmail, $this->fromName)
                  ->setTo($User->getEmail(), $User->getUsername())
                  ->setBody($htmlBody, 'text/html');
        
        $this->swiftMailer->send($mesage);
    }
}
