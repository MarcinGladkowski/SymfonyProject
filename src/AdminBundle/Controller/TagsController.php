<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use BlogBundle\Entity\Tag;
use AdminBundle\Form\Type\TaxonomyType;


class TagsController extends Controller
{
    private $deleteTokenName = 'delete-%d-tag';
    
    /**
     * @Route(
     *      "/list/{page}", 
     *      name="admin_tagsList",
     *      requirements={"page"="\d+"},
     *      defaults={"page"=1}
     * )
     * 
     * @Template()
     */
    public function indexAction(Request $Request, $page) {
        
        $TagRepository = $this->getDoctrine()->getRepository('BlogBundle:Tag');
        
        $qb = $TagRepository->getQueryBuilder();
        
        $limit = $this->container->getParameter('admin.pagination_limit');
        
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $page, $limit);
        
        return array(
            'currPage' => 'taxonomies',
            'pagination' => $pagination,
            'deleteTokenName' => $this->deleteTokenName,
//            'csrfProvider' => $this->get('form.csrf_provider')
        );
    }
    
    
    /**
     * @Route(
     *      "/form/{id}", 
     *      name="admin_tagForm",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=NULL}
     * )
     * 
     * @Template()
     */
    public function formAction(Request $Request, Tag $Tag = NULL) {
        
        if(NULL === $Tag){
            $Tag = new Tag();
            $newTag = TRUE;
        }
        
        $form = $this->createForm(new TaxonomyType(), $Tag);
        
        $form->handleRequest($Request);
        
        if($form->isValid()){
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($Tag);
            $em->flush();

            $flashMessage = (isset($newTag)) ? 'Poprawnie dodano nowy tag' : 'Poprawiono tag';

            $this->get('session')->getFlashBag()->add('success', $flashMessage);

            return $this->redirect($this->generateUrl('admin_tagForm', array(
                'id' => $Tag->getId()
            )));

        }
        
        return array(
            'currPage' => 'taxonomies',
            'form' => $form->createView(),
            'tag' => $Tag
        );
    }
    
    
    /**
     * @Route(
     *      "/delete/{id}", 
     *      name="admin_tagDelete",
     *      requirements={"id"="\d+"}
     * )
     * 
     * @Template()
     */
    public function deleteAction($id) {
        
        $tokenName = sprintf($this->deleteTokenName, $id);
        $csrfProvider = $this->get('form.csrf_provider');
        
        if(!$csrfProvider->isCsrfTokenValid($tokenName, $token)){
            $this->get('session')->getFlashBag()->add('error', 'Niepoprawny token akcji!');
            
        }else{
            
            $Tag = $this->getDoctrine()->getRepository('BlogBundle:Tag')->find($id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($Tag);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Poprawnie usunięto tag.');
        }
        
        return $this->redirect($this->generateUrl('admin_tagsList'));
    }
}
