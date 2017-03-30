<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TaxonomyRepository extends EntityRepository {
    
   public function getQueryBuilder(array $params = array()){
       return $this->createQueryBuilder('t');
   }
   
   public function getAsArray(){
       return $this->createQueryBuilder('t')
               ->select('t.id, t.name')
               ->getQuery()
               ->getArrayResult();
   }
}
