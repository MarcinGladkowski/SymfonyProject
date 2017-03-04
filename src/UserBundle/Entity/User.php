<?php

namespace UserBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements AdvancedUserInterface, \Serializable {
    
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length = 20, unique = true)
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length = 120, unique = true)
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length = 64)
     */
    private $password;
    
    private $plainPassword;
    
    /**
     * @ORM\Column(name="account_not_expired",type="boolean")
     */
    private $accountNonExpired = true;
    
    /**
     * @ORM\Column(name="account_non_Locked",type="boolean")
     */
    private $accountNonLocked = true;
    
    /**
     * @ORM\Column(name="credentials_non_Expired",type="boolean")
     */
    private $credentialsNonExpired = true;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = false;
    
    /**
     * @ORM\Column(type="array")
     */
    private $roles;
    
    /**
     * @ORM\Column(name="action_token", type="string", length = 20, nullable=true)
     */
    private $actionToken;
    
    /**
     * @ORM\Column(name="register_date", type="datetime")
     */
    private $registerDate;
    
    /**
     * @ORM\Column(type="string", length = 100, nullable=true)
     */
    private $avatar;
    
    function __construct() {
        $this->registerDate = new \DateTime();
    }


    public function isAccountNonExpired() {
        return $this->accountNonExpired;
    }

    public function isAccountNonLocked() {
        return $this->accountNonLocked;
    }

    public function isCredentialsNonExpired() {
        return $this->credentialsNonExpired;
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getSalt() {
        return null;
    }

    public function getUsername() {
        return $this->username;
    }
    
    function getId() {
        return $this->id;
    }

    function getEmail() {
        return $this->email;
    }

    function getPlainPassword() {
        return $this->plainPassword;
    }

    function getAccountNonExpired() {
        return $this->accountNonExpired;
    }

    function getAccountNonLocked() {
        return $this->accountNonLocked;
    }

    function getCredentialsNonExpired() {
        return $this->credentialsNonExpired;
    }

    function getEnabled() {
        return $this->enabled;
    }

    function getActionToken() {
        return $this->actionToken;
    }

    function getRegisterDate() {
        return $this->registerDate;
    }

    function getAvatar() {
        return $this->avatar;
    }

    function setId($id) {
        $this->id = $id;
    }
    
    function setUsername($username) {
        $this->username = $username;
    }
    
    function setPassword($password) {
        $this->password = $password;
    }

    function setRoles($roles) {
        $this->roles = $roles;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    function setAccountNonExpired($accountNonExpired) {
        $this->accountNonExpired = $accountNonExpired;
    }

    function setAccountNonLocked($accountNonLocked) {
        $this->accountNonLocked = $accountNonLocked;
    }

    function setCredentialsNonExpired($credentialsNonExpired) {
        $this->credentialsNonExpired = $credentialsNonExpired;
    }

    function setEnabled($enabled) {
        $this->enabled = $enabled;
    }

    function setActionToken($actionToken) {
        $this->actionToken = $actionToken;
    }

    function setRegisterDate($registerDate) {
        $this->registerDate = $registerDate;
    }

    function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function serialize() {
        return serialize(array(
           $this->id,
           $this->username,
           $this->password
        ));
    }

    public function unserialize($serialized) {
        list(
           $this->id,
           $this->username,
           $this->password     
        ) = unserialize($serialized);
    }

}
