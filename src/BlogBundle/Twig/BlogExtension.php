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
            new \Twig_SimpleFunction('print_Main_Menu', array($this, 'printMainMenu'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('print_Tags_Cloud', array($this, 'tagsCloud'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('print_Recent_Commented', array($this, 'recentCommented'), array('is_safe' => array('html'))),
        );
    }
    
    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('b_shorten', array($this, 'shorten'), array('is_safe' => array('html'))),
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
        $tagsList = $TagRepo->getTagsListOcc();
        $tagsList = $this->prepareTagsCloud($tagsList, $limit, $minFontSize, $maxFontSize);
        
                
        return $this->enviroment->render('BlogBundle:Template:tagsCloud.html.twig', array(
            'tagsList' => $tagsList
        ));
    }
    
    protected function prepareTagsCloud($tagsList, $limit, $minFontSize, $maxFontSize){
        $occs = array_map(function($row){
            return (int)$row['occ'];
        }, $tagsList);
        
        $minOcc = min($occs);
        $maxOcc = max($occs);
        
        $spread = $maxOcc - $minOcc;
        
        $spread = ($spread == 0) ? 1 : $spread;
        
        usort($tagsList, function($a, $b){
            $ao = $a['occ'];
            $bo = $b['occ'];
            
            if($ao === $bo) return 0;
            
            return ($ao < $bo) ? 1 : -1;
        });
        
        $tagsList = array_slice($tagsList, 0, $limit);
        
        shuffle($tagsList);
        
        foreach($tagsList as &$row){
            $row['fontSize'] = round(($minFontSize + ($row['occ'] - $minOcc) * ($maxFontSize - $minFontSize) / $spread), 2);
        }

        return $tagsList;
    }
    
    public function shorten($text, $length = 3, $wrapTag = 'p'){
        $text = strip_tags($text);
        $text = substr($text, 0, $length).'[...]';
        $openTag = "<$wrapTag>";    
        $closeTag = "<$wrapTag>";
        
        return $openTag.$text.$closeTag;
    }
    
    public function recentCommented($limit = 3) {
        
        $PostRepo = $this->doctrine->getRepository('BlogBundle:Post');
        
        $postList = $PostRepo->getRecentCommented($limit);
        
        return $this->enviroment->render('BlogBundle:Template:recentCommented.html.twig', array(
            'PostList' => $postList
        ));
        
    }
    
}
