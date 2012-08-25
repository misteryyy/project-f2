<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

class NotificationFacade {
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		$this->em = $em;
			
	}
	
	/**
	 * Adds log message to the user activity
	 * @param unknown_type $user
	 * @param unknown_type $message
	 */
	public function addUserNotification($user,$message,$rate = 0,$type = \App\Entity\UserLog::TYPE_SYSTEM){	
		$lm = new \App\Entity\UserLog($message,$type,$rate);
		$lm->setUser($user);
		$this->em->persist($lm);
		$this->em->flush();
	}
	
	/**
	 * Adds log message to the user activity
	 * @param unknown_type $user
	 * @param unknown_type $message
	 */
	public function addProjectNotification($project,$message,$rate = 0,$type = \App\Entity\UserLog::TYPE_SYSTEM){
		$lm = new \App\Entity\ProjectLog($message,$type,$rate);
		$lm->setProject($project);
		$this->em->persist($lm);
		$this->em->flush();
	}
	
	
	
	
		
	
}

?>