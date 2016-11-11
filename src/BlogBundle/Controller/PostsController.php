<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller {   
    
    protected $itemsLimit = 2;
    
    /**
     * @Route("/{page}", name = "blog_index", defaults = {"page" = 1}, requirements = {"page" = "\d+"})
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function indexAction($page)
    {   
        
       $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC'
        ), $page);
        
        return array(
            'pagination' => $pagination,
            'listTitle' => 'Najnowsze wpisy'
            
            
        );
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
     * @Route("/category/{slug}/{page}", 
     * name = "blog_category", 
     * defaults = {"page" = 1}, 
     * requirements = {"page" = "\d+"})
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function categoryAction($slug, $page)
    {
        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'categorySlug' => $slug
        ), $page);

        $CategoryRepo = $this->getDoctrine()->getRepository('BlogBundle:Category');
        $Category = $CategoryRepo->findOneBySlug($slug);
        
        
        
        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy w kategorii "%s"', $Category->getName())
        );
    }
    
     /**
     * @Route("/tag/{slug}/{page}", name = "blog_tag", defaults = {"page" = 1}, requirements = {"page" = "\d+"})
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function tagAction($slug, $page)
    {
        $TagRepo = $this->getDoctrine()->getRepository('BlogBundle:Tag');
        $Tag = $TagRepo->findOneBySlug($slug);
        
        $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
            'tagSlug' => $slug
        ), $page);

        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wpisy z tagiem "%s"', $Tag->getName())
        );
    }
    
    protected function getPaginatedPosts(array $params = array(), $page){
        
        $PostRepo = $this->getDoctrine()->getRepository('BlogBundle:Post');
        $qb = $PostRepo->getQueryBuilder($params);
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $this->itemsLimit);
        
        return $pagination;

    }
}
