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
    
    /**
     * @ORM\ManyToOne(
     *                 targetEntity = "Category",
     *                 inversedBy = "posts"
     * )
     * 
     * @ORM\JoinColumn(
     *          name = "category_id", 
     *          referencedColumnName = "id", 
     *          onDelete = "SET NULL"
     * )
     */
    private $category;
    
    /**
     * @ORM\ManyToMany(
     *      targetEntity = "Tag", 
     *      inversedBy = "posts"
     * )
     * @ORM\JoinTable(name = "blog_post_tags")
     */
    private $tags;
    
    /**
     * @ORM\Column(type="string", length=180)
     */
    private $author;
    
    /**
     * @ORM\Column(name = "create_date", type="datetime")
     */
    private $createDate;
    
     /**
     * @ORM\Column(name = "published_date", type="datetime", nullable=true)
     */
    private $publishedDate = null;
    
}
