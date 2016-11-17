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
            new \Twig_SimpleFunction('print_Categories_List', array($this, 'printCategoriesList'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('print_Main_Menu', array($this, 'printMainMenu'), array('is_safe' => array('html')))
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
    
    public function printMainMenu(){
        $mainMenu = array(
            'home' => 'blog_index',
            'o mnie' => 'blog_about',
            'kontakt' => 'blog_contact'
        );
        
        return $this->enviroment->render('BlogBundle:Template:mainMenu.html.twig', array(
            'mainMenu' => $mainMenu
        ));
    }
    
    public function tagsCloud($limit = 40, $minFontSize = 1, $maxFontSize = 3.5){
        $TagRepo = $this->doctrine->getRepository('BlogBundle:Tag');
        $tagList = $TagRepo->getTagsListOcc();
    }
    
    protected function prepareTagsCloud($tagsList, $limit, $minFontSize, $maxFontSize){
        $occs = array_map(function($row){
            return (int)$row['occ'];
        }, $tagsList);
        
        $minOcc = min($occs);
        $maxOcc = max($occs);
        
        $spread = $maxOcc - $minOcc;
        
        $spread = ($spread == 0) ? 1 : $spread;
        
        // 4:22
     }
    
}
