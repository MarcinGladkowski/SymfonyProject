<?php

namespace BlogBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Tag;

class TagsFixtures extends AbstractFixture implements OrderedFixtureInterface{
    public function load(ObjectManager $manager) {
       
        
        $tagList = array(
            'osobowe',
            'odrzutowe',
            'wojskowe',
            'kosmiczne',
            'tajne2',
            'osobowe2',
            'odrzutowe2',
            'wojskowe2',
            'kosmiczne2',
            'tajne3',
            'osobowe3',
            'odrzutowe3',
            'wojskowe3',
            'kosmiczne3',
            'tajne',
        );
        foreach ($tagList as $name) {
            $Tag = new Tag();
            
            $Tag->setName($name);
               
 
        $manager->persist($Tag);
        
        $this->addReference('tag_'.$name, $Tag);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 0;
    }

}
