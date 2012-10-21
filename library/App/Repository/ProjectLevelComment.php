<?php
namespace App\Repository;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityRepository;
 
class ProjectLevelComment extends EntityRepository
{
    public function findThemAll($project)
    {
        $stmt = 'SELECT c FROM App\Entity\ProjectLevelComment c ORDER BY c.created DESC';
        return $this->_em->createQuery($stmt)->getResult();
    }

}
