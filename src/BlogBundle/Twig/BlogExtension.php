<?php

namespace BlogBundle\Twig;

/**
 * Description of BlogExtension
 *
 * @author Marcin<Marcin>
 */
class BlogExtension extends \Twig_Extension {
    
    /**
     *
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;
    
    /**
     *
     * @var \Twig_Enviroment 
     */
    private $enviroment;
    
    private $categoriesList;

    function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine) {
        $this->doctrine = $doctrine;
    }
    
    public function initRuntime(\Twig_Environment $enviroment){
        $this->enviroment = $enviroment;
    }
    
    public function getName()
    {
        return 'blog_extension';
    }
    
    public function getFunctions() {
        return array (
            new \Twig_SimpleFunction('print_Categories_List', array($this, 'printCategoriesList'), array('is_safe' => array('html')))
        );
    }
    
    public function printCategoriesList(){
        if(!isset($this->categoriesList)){
            $CategoryRepo = $this->doctrine->getRepository('BlogBundle:Category');
            $this->categoriesList = $CategoryRepo->findAll();
        }

        return $this->enviroment->render('BlogBundle:Template:categoriesList.html.twig', array(
            'categoriesList' => $this->categoriesList
        ));
    }
    
}
