<?php

namespace BlogBundle\DataFixTures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Post;

class PostsFixtures extends AbstractFixture implements OrderedFixtureInterface{
    public function load(ObjectManager $manager) {
        
        $postsList = array(
            array(
                'title' => 'Jakiś tytuł',
                'content' => 'Jakaś treść',
                'category' => 'osobowe',
                'tags' => array('kosmiczne2', 'tajne3'),
                'author' => 'Adam Nowak',
                'createDate' => '2012-01-01 12:11:12',
                'publishedDate' => NULL,
            ),
            array(
                'title' => 'Jakiś tytuł2',
                'content' => 'Jakaś treść2',
                'category' => 'tajne',
                'tags' => array('kosmiczne', 'tajne'),
                'author' => 'Marcin Nowak',
                'createDate' => '2014-01-01 12:11:12',
                'publishedDate' => NULL,
            ),
             array(
                'title' => 'Jakiś tytuł3',
                'content' => 'Jakaś treść2',
                'category' => 'tajne',
                'tags' => array('kosmiczne', 'tajne'),
                'author' => 'Marcin Nowak',
                'createDate' => '2014-01-01 12:11:12',
                'publishedDate' => NULL,
            ),
             array(
                'title' => 'Jakiś tytuł4',
                'content' => 'Jakaś treść2',
                'category' => 'tajne',
                'tags' => array('kosmiczne', 'tajne'),
                'author' => 'Marcin Nowak',
                'createDate' => '2014-01-01 12:11:12',
                'publishedDate' => '2014-01-01 12:11:12',
            ),
            array(
                'title' => 'Jakiś tytuł5',
                'content' => 'Jakaś treść5',
                'category' => 'tajne',
                'tags' => array('kosmiczne', 'tajne'),
                'author' => 'Marcin Gładkowski',
                'createDate' => '2014-01-01 13:11:12',
                'publishedDate' => '2014-01-01 13:11:12',
            ),
            array(
                'title' => 'Jakiś tytuł6',
                'content' => 'Jakaś treść6',
                'category' => 'tajne',
                'tags' => array('kosmiczne', 'tajne'),
                'author' => 'Marcin Nowakowski',
                'createDate' => '2015-01-01 13:11:12',
                'publishedDate' => '2015-01-01 13:11:12',
            ),
        );
       
        foreach ($postsList as $details) {
        
            $Post = new Post();
            
            $Post->setTitle($details['title'])
                 ->setContent($details['content'])
                 ->setAuthor($details['author'])
                 ->setCreateDate(new \DateTime($details['createDate']));
                              
            if(null !== $details['publishedDate']){        
                 $Post->setPublishedDate(new \DateTime($details['publishedDate']));
            }
            $Post->setCategory($this->getReference('category_'.$details['category']));
                    
            foreach($details['tags'] as $tagName) {
                 $Post->addTag($this->getReference('tag_'.$tagName));
            }
        $manager->persist($Post);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
