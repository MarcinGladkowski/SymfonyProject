<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller {   
    
    /**
     * @Route("/{page}", name = "blog_index", defaults = {"page" = 1}, requirements = {"page" = "\d+"})
     * @Template()
     */
    public function indexAction($page)
    {
        return array();
    }
    
    /**
     * @Route("/{slug}", name = "blog_post")
     * @Template()
     */
    public function postAction($slug)
    {
        return array();
    }
    
     /**
     * @Route("/category/{slug}/{page}", name = "blog_category", defaults = {"page" = 1}, requirements = {"page" = "\d+"})
     * @Template()
     */
    public function categoryAction($slug)
    {
        return array();
    }
    
     /**
     * @Route("/tag/{slug}/{page}", name = "blog_tag", defaults = {"page" = 1}, requirements = {"page" = "\d+"})
     * @Template()
     */
    public function tagAction($slug)
    {
        return array();
    }
}
