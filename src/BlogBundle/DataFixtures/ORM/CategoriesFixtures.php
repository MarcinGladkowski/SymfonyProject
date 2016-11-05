<?php

namespace BlogBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Tag;

class TagsFixtures extends AbstractFixture{
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
            'tajne4',
        );
        foreach ($tagList as $key => $name) {
            $Tag = new Tag();
            
            $Tag->setName($name)
                     ->setSlug($name);
        
        $manager->persist($Tag);
        }
        $manager->flush();
    }
}
