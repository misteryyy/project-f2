<?php
namespace App\Repository\Launch;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityRepository;
 
class Newsletter extends EntityRepository
{
    public function findAll()
    {
        $stmt = 'SELECT q FROM App\Entity\Launch\Newsletter q ORDER BY q.id DESC';
        return $this->_em->createQuery($stmt)->getResult();
    }
    
    
 
    
    
       
    
}
