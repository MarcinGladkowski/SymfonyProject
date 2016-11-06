<?php

namespace BlogBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Category;

class CategoriesFixtures extends AbstractFixture implements OrderedFixtureInterface{
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
            
            $Category->setName($name);
                 
            
        $manager->persist($Category);
        
        $this->addReference('category_'.$key, $Category);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }

}
