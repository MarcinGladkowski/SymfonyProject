<?php

namespace BlogBundle\Entity; 

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_posts")
 * @author Marcin
 */
class Post {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $title;
    
    /**
     * @ORM\Column(type="string", length=120, unique=true)
     */
    private $slug;
    
    /**
     * @ORM\Column(type="text")
     */
    private $content;
    
    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $thumbnail = null;
    
    private $category;
    
    private $tags;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $author;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;
    
     /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishedDate = null;
    
}
