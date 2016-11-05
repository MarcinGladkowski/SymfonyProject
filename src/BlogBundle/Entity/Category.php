<?php

namespace BlogBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="blog_categories")
 * @author Marcin
 */
class Tag extends AbstractTaxonomy {
  
}
