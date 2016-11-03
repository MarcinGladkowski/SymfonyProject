<?php

namespace BlogBundle\Entity; 

/**
 *
 * @author Marcin
 */
class Post {
    
    private $id;
    
    private $title;
    
    private $slug;
    
    private $content;
    
    private $thumbnail = null;
    
    private $category;
    
    private $tags;
    
    private $author;
    
    private $createDate;
    
    private $publishedDate;
    
}
