<?php

namespace BlogBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Category;

class CategoriesFixtures extends AbstractFixture{
    public function load(ObjectManager $manager) {
       
        
        $categoriesList = array(
            'osobowe' => 'Samoloty osobowe i pasażerskie',
            'odrzutowe' => 'Samoloty odrzutowe',
            'wojskowe' => 'Samoloty wojskowe', 
            'kosmiczne' => 'Promy kosmiczne',
            'tajne' => 'Tajne rozwiązania'
        );
        foreach ($categoriesList as $key => $name) {
            $Category = new Category();
            
            $Category->setName($name)
                     ->setSlug($name);
        
        $manager->persist($Category);
        }
        $manager->flush();
    }
}
