<?php

namespace BlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use BlogBundle\Entity\Comment;
use BlogBundle\Form\CommentType;

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
     * @Route("/search/{page}", 
     * name = "blog_search", 
     * defaults = {"page" = 1}, 
     * requirements = {"page" = "\d+"})
     * 
     * @Template("BlogBundle:Posts:postsList.html.twig")
     */
    public function searchAction(Request $Request, $page)
    {   
        
       $searchParam = $Request->query->get('search');
        
       $pagination = $this->getPaginatedPosts(array(
            'status' => 'published',
            'orderBy' => 'p.publishedDate',
            'orderDir' => 'DESC',
           'search' => $searchParam
        ), $page);
        
        return array(
            'pagination' => $pagination,
            'listTitle' => sprintf('Wyniki wyszukiwania frazy "%s"', $searchParam),
            'searchParam' => $searchParam
        );
    }
    
    /**
     * @Route("/{slug}", name = "blog_post")
     * @Template()
     */
    public function postAction(Request $request, $slug)
    {   
        
        $Session = $this->get('session');
        
        $PostRepo = $this->getDoctrine()->getRepository('BlogBundle:Post');
        
        $Post = $PostRepo->getPublishedPost($slug);
        
        if(null === $Post){
            throw $this->createNotFoundException('Post nie został odnaleziony');
        }
        
        if(null !== $this->getUser()){
            
            $Comment = new Comment();
            $Comment->setAuthor($this->getUser());
            $Comment->setPost($Post);
            
            
            $commentForm = $this->createForm(CommentType::class, $Comment);
            
            if($request->isMethod('POST')){
            
             $commentForm->handleRequest($request);

                    if ($commentForm->isSubmitted() && $commentForm->isValid()) {
                        
                                $em = $this->getDoctrine()->getManager();
                                $em->persist($Comment);
                                $em->flush();
                                
                                $Session->getFlashBag()->add('success', 'Komentarz dodany!');
                                
                                $redirecUrl = $this->generateUrl('blog_post', array(
                                        'slug' => $Post->getSlug()
                                ));
                                
                                return $this->redirect($redirecUrl);  
                    }

            }
        }
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $csrfProvider = $this->get('form.csrf_provider');
        }
         
        return array(
            'post' => $Post,
            'commentForm' => isset($commentForm) ? $commentForm->createView() : null,
            'csrfProvider' => isset($csrfProvider) ? $csrfProvider : null,
            'tokenName' => 'delCom$d'
        );
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
    
    
    /**
     * @Route("/post/comment/delete/{commentId}/{token}",
     * name = "blog_deleteComment")
     */
    public function deleteCommentAction(Request $request, $commentId, $token) {
        
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            throw $this->createAccessDeniedException('Nie masz uprawnień do tego zadania!');
        }
        
        $validToken = sprintf('delCom$d', $commentId);
        
        if(!$this->get('form.csrf_provider')->isCsrfTokenValid($validToken, $token)){
            throw $this->createAccessDeniedException('Błędny token akcji!');
        }
        
        
        $commentId = $request->query->get('commentId');
        
        $comments = $this->getDoctrine()->getRepository('BlogBundle:Comment');
        
        $Comment = $comments->findOneBy(array('id' => $commentId));
       
        
        if(null === $Comment){
            throw $this->createNotFoundException('Komentarz nie został odnaleziony');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($Comment);
        $em->flush();
        
        return new \Symfony\Component\HttpFoundation\JsonResponse(array(
            'status' => 'ok',
            'message' => 'Komentarz został usunięty'
        ));
       
    }
}
