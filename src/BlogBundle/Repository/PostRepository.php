<?php

namespace BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository {
    
    public function getQueryBuilder(array $params = array()){
        
        $qb = $this->createQueryBuilder('p')
                   ->select('p, c, t')
                   ->leftJoin('p.category', 'c')
                   ->leftJoin('p.tags', 't');
        
        if(!empty($params['status'])){
            if('published' == $params['status']){
                $qb->where('p.publishedDate <= :currDate AND p.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            } else if ('unpublished' == $params['status']){
                $qb->where('p.publishedDate > :currDate OR p.publishedDate IS NOT NULL')
                        ->setParameter('currDate', new \DateTime());
            }
        }
        
        if(!empty($params['orderBy'])){
            $orderDir = !empty($params['orderDir']) ? $params['orderDir'] : NULL;
            $qb->orderBy($params['orderBy'], $orderDir);
        }
        
        return $qb;
        
    }
}
