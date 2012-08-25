<?php
namespace App\Facade\Project;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

class ProjectBoardFacade {
	
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $userFacade;
	private $facadeNotification;
	
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		
		$this->em = $em;
		$this->userFacade = new \App\Facade\UserFacade($em);
		$this->facadeNotification = new \App\Facade\NotificationFacade($em);
		
	}
	

	/**
	 * Add new project board message
	 * @param unknown_type $user_id
	 * @param unknown_type $project_id
	 * @param unknown_type $data
	 * @param unknown_type $files
	 * @throws \Exception
	 */
	public function addComment($user_id,$project_id,$data = array(),$files = array()){
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
	
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ($project_id);
		if(!$project){
			throw new \Exception("Can't find this project.");
		}
		
		$newComment = new \App\Entity\ProjectBoardComment($user, $project,$data['title'] ,$data['content']);
		// add files
		if(count($files) > 0 ){
			foreach ($files as $file){
				$newFile = new \App\Entity\ProjectBoardFile($file['name'],$file['type'],$file['size'],$file['file']);
				$newComment->addFile($newFile);			
			}
		}

		$this->facadeNotification->addUserNotification($user,"Sent message to Project Board in ".$project->getProjectFullUrl(),2);
		$this->em->persist($newComment);
		$this->em->flush();
	}
	
	
	/*
	 * Return the first layer of commments
	*/
	public function findCommentsForProject($project_id){
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById($project_id);
		if(!$project){
			throw new \Exception("Can't find this project.");
		}
	
		$stmt = 'SELECT c FROM App\Entity\ProjectBoardComment c WHERE c.project = ?1';
		$stmt .= 'ORDER BY c.created DESC  ';
	
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $project_id);
			
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
	
		$iterator = $paginator->getIterator();
	
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	}
	
	/**
	 * Find file for project comment
	 * @param unknown_type $project_id
	 * @param unknown_type $file_id
	 * @throws \Exception
	 */
	public function findFileForComment($project_id,$file_id){
		
		
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById($project_id);
		if(!$project){
			throw new \Exception("Can't find this project.");
		}

		$file = $this->em->getRepository ('\App\Entity\ProjectBoardFile')->findOneById($file_id);
		if(!$file){
			throw new \Exception("Can't find this file.");
		}

		return $file;
		
	}
	
	
	/*
	 * Return the first layer of commments
	*/
	public function findUnasweredCommentsForProject($project_id){
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById($project_id);
		if(!$project){
			throw new \Exception("Can't find this project.");
		}
		$stmt = 'SELECT c FROM App\Entity\ProjectComment c WHERE c.project = ?1 AND c.priority = 1';
		$stmt .= 'ORDER BY c.created DESC  ';
	
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $project_id);
			
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
	
		$iterator = $paginator->getIterator();
	
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	}
	
	
	
	
}

?>