<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_tags")
 * @author Marcin
 */
class Category extends AbstractTaxonomy {
    
    /**
     * @ORM/ManyToMany(targetEntity = "Post", mappedBy = "Tags")
     */
    protected $posts;
  
}
