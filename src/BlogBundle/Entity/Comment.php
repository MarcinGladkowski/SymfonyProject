<?php

namespace BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_comments")
 */
class Comment {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(
     *                 targetEntity = "Post",
     *                 inversedBy = "comments"
     * )
     * 
     * @ORM\JoinColumn(
     *          name = "post_id", 
     *          referencedColumnName = "id", 
     *          nullable = false
     * )
     */
    private $post;
    
    /**
     * @ORM\ManyToOne(
     *                 targetEntity = "UserBundle\Entity\User",
     *       
     * )
     * 
     * @ORM\JoinColumn(
     *          name = "author_id", 
     *          referencedColumnName = "id", 
     *          nullable = false
     * )
     */
    private $author;
    
     /**
     * @ORM\Column(name = "create_date", type="datetime")
     */
    private $createDate;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(max = 1000)
     */
    private $comment;
    
    function __construct() {
        $this->createDate = new \DateTime();
    }

    
    function getId() {
        return $this->id;
    }

    function getPost() {
        return $this->post;
    }

    function getAuthor() {
        return $this->author;
    }

    function getCreateDate() {
        return $this->createDate;
    }

    function getComment() {
        return $this->comment;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setPost($post) {
        $this->post = $post;
    }

    function setAuthor($author) {
        $this->author = $author;
    }

    function setCreateDate($createDate) {
        $this->createDate = $createDate;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }


}
