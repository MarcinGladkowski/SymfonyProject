<?php

namespace UserBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * 
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User implements AdvancedUserInterface, \Serializable {
    
    const DEFAULT_AVATAR = 'default-avatar.jpg';
    const UPLOAD_DIR = 'uploads/avatars/'; 
    
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length = 20, unique = true)
     * @Assert\NotBlank(
     *      groups = {"Registration", "ChangeDetails"}
     * )
     * @Assert\Length(
     *                min=5, 
     *                max=20,
     *                groups = {"Registration", "ChangeDetails"}     
     * )
     */
    private $username;
    
    /**
     * @ORM\Column(type="string", length = 120, unique = true)
     * @Assert\NotBlank(groups = {"Registration"}  )
     * @Assert\Email(groups = {"Registration"}  )
     * @Assert\Length(max=120, 
     *                 groups = {"Registration", "ChangeDetails"}   
     * )
     */
    private $email;
    
    /**
     * @ORM\Column(type="string", length = 64)
     * @Assert\Length(
     *          max=20,
     *          groups = {"Registration"}          
     * )
     */
    private $password;
    
    /**
     * @Assert\NotBlank(
     *          groups = {"Registration", "ChangePassword"}     
     * )
     * @Assert\Length(
     *               min=8,
     *               groups = {"Registration", "ChangePassword"}
     * )
     */
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
    
    /**
     * @var type UploadedFile
     * 
     * @Assert\Image(
     *         minWidth = 50,
     *         maxWidth = 50,
     *          minHeight = 50,
     *          maxHeight = 150,
     *          maxSize = "1M",
     *          groups = {"ChangeDetails"}       
     * )
     */
    private $avatarFile;
    
    private $avatarTemp;
    
    /**
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $updateDate;
            
    function __construct() {
        $this->registerDate = new \DateTime();
    }
    
    function getAvatarFile() {
        return $this->avatarFile;
    }

    function setAvatarFile(UploadedFile $avatarFile) {
        $this->updateDate = new \DateTime();
        $this->avatarFile = $avatarFile;
        
        return $this;
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
        
        if(empty($this->roles)){
            return array(
                "ROLE_USER"
            );
        }
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
        
        if(null == $this->avatar ){
            return User::UPLOAD_DIR.User::DEFAULT_AVATAR;
        }
        
        return User::UPLOAD_DIR.$this->avatar;
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
    
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     */
    public function preSave() {
        if(null !== $this->getAvatarFile()){
            if(null !== $this->avatar){
                $this->avatarTemp = $this->avatar;
            }
            
            $avatarName = sha1(uniqid(null, true));
            $this->avatar = $avatarName.'.'.$this->getAvatarFile()->guessExtension();
            
        }
        
    }
    
     /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public function postSave(){
        if(null !== $this->getAvatarFile()){
            
        
            $this->getAvatarFile()->move($this->getUploadRootDir(), $this->avatar);

            unset($this->avatarFile);
            
            
            if(null !== $this->avatarTemp){
                unlink($this->getUploadRootDir().$this->avatarTemp);
                unset($this->avatarTemp);
            }
        }
    }
    
    protected function getUploadRootDir(){
        return __DIR__.'/../../../../web/'.User::UPLOAD_DIR;
    }
}
