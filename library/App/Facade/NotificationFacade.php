<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

class NotificationFacade {
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $userFacade;
	private $taskFacade;
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		
		$this->em = $em;
		$this->userFacade = new \App\Facade\UserFacade($em);
		$this->taskFacade = new \App\Facade\Project\TaskFacade($em);
		
	}
	
	/**
	 * Adds log message to the user activity
	 * @param unknown_type $user
	 * @param unknown_type $message
	 */
	public function addUserNotification($user_id,$message,$type = \App\Entity\UserLog::TYPE_PRIVATE){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		
		$lm = new \App\Entity\UserLog($message,$type);
		$lm->setUser($user);
		$this->em->persist($lm);
		$this->em->flush();
	}
	
	
	/**
	 * Return all log information for user
	 * @param unknown_type $user_id
	 * @throws \Exception
	 */
	public function findLogForUser($user_id){
	
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		return $this->em->getRepository ('\App\Entity\UserLog')->findByUser($user);
	
	}
	
	/**
	 * Return all log information for user
	 * @param unknown_type $user_id
	 * @throws \Exception
	 */
	public function findPublicLogForUserPaginator($user_id){
	
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		$stmt = 'SELECT u FROM App\Entity\UserLog u WHERE u.user = ?1 AND u.type = ?2 ORDER BY u.created ';
		//$stmt .= 'ORDER BY a.created, a.roleName DESC';
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $user_id);
		$query->setParameter(2, \App\Entity\UserLog::TYPE_PRIVATE);
	
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	
	}
	
	
	
}

?>