<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CategoryRepository")
 * @author Marcin
 */
class Category extends AbstractTaxonomy {
  
    /**
     * @ORM\OneToMany(
     *      targetEntity = "Post", 
     *      mappedBy = "category"
     * )
     */
    protected $posts;
    
    
}
