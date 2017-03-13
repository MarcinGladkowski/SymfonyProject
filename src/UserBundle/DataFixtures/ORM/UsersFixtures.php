<?php

namespace UserBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UsersFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface{
    
    /**
     *
     * @var ContainerInterface
     */
    private $container;
    
    public function getOrder() {
        return 0;
    }
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

        
    public function load(ObjectManager $manager) {
       
        $userList = array(
            array(
                'nick' => 'Marcin',
                'email' => 'marcingladkowski@gmail.com',
                'password' => '123',
                'role' => 'ROLE_USER'
            ),
            array(
                'nick' => 'Marcin2',
                'email' => 'marcingladkowski2@gmail.com',
                'password' => '1234',
                'role' => 'ROLE_USER'
            ),
            array(
                'nick' => 'Marcin3',
                'email' => 'marcingladkowski3@gmail.com',
                'password' => '12345',
                'role' => 'ROLE_USER'
            )
        );
        
        $encoderFactory = $this->container->get('security.encoder_factory');
        
        foreach($userList as $userDetails){
            $User = new User();
            
            $password = $encoderFactory->getEncoder($User)->encodePassword($userDetails['password'], null);
            
            $User->setUsername($userDetails['nick']);
            $User->setEmail($userDetails['email']);
            $User->setPassword($password);
            $User->setRoles(array($userDetails['role']));
            $User->setEnabled(true);
            
            $this->addReference('user-'.$userDetails['nick'], $User);
            
            $manager->persist($User);
        }
        
        $manager->flush();
        
    }
}
