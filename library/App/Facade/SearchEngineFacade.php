<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;
use Doctrine\ORM\Query\ResultSetMapping;

class SearchEngineFacade {
	
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $facadeUser; 
	private $facadeProject;
	private $facadeTeam;
	
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		$this->em = $em;
		$this->facadeUser = new \App\Facade\UserFacade($this->em);
		$this->facadeProject = new \App\Facade\ProjectFacade($this->em);
		$this->facadeTeam = new \App\Facade\Project\TeamFacade($this->em);
		 
	}

	
	/**
	 * Search in indexes
	 * @param unknown_type $query
	 */
	public function findUsersPaginator($query){
		\Zend_Search_Lucene_Analysis_Analyzer::setDefault(
				new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ());
		\Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
		 
		$index = \Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/members');
		$hits = $index->find($query);
		
		$users = array();
		foreach ($hits as $h){
			echo $h->user_id;
			$users[] = $this->findOneUser($h->user_id);
		}
		
		return \Zend_Paginator::factory($users); 
	}
	
	/**
	 * Search in indexes
	 * @param unknown_type $query
	 */
	public function findProjectPaginator($query){
		\Zend_Search_Lucene_Analysis_Analyzer::setDefault(
				new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive ());
		\Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
			
		$index = \Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/projects');
		$hits = $index->find($query);
	
		$projects = array();
		foreach ($hits as $h){
			$projects[] = $this->facadeProject->findOneProject($h->project_id);
		}
	
		return \Zend_Paginator::factory($projects);
	}
	
	
	
	/**
	 * Build start index form member  
	 * @return number of non deleted index in this search engine
	 */
	public function buildMemberIndexes(){
		$paginator = $this->facadeUser->findAllUsers();	
		\Zend_Search_Lucene_Analysis_Analyzer::setDefault(new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ());
		$index = \Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes/members');
				
		// go through all members in the index file
		foreach ($paginator as $user){
			$this->addUserIndex($user); // add new index to the index
		}
		
		// optimize the index
		$index->optimize();
		return $index->numDocs(); 
	}
	
	/**
	 * Build start index for project
	 * @return number of non deleted index in this search engine
	 */
	public function buildProjectIndexes($options = array()){
		
		
		if(!empty( $options['category']) ) $paginator = $this->facadeProject->findAllProjectsByCategoryPaginator($options['category']);
		else $paginator = $this->facadeProject->findAllProjects();
	
		\Zend_Search_Lucene_Analysis_Analyzer::setDefault(new \Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive ());
		$index = \Zend_Search_Lucene::create(APPLICATION_PATH . '/indexes/projects');
	
		// go through all members in the index file
		foreach ($paginator as $project){
			$this->addProjectIndex($project); // add new index to the index
		}
	
		// optimize the index
		$index->optimize();
	
		return $index->numDocs();
	}
	
	public function addProjectIndex($project){
		// open index
		$index = \Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/projects');

		
		// if level 1 display all logos
		if($project->level == 1)
			$freePositions = \App\Entity\ProjectRole::getRolesArray();
		
		if($project->level == 2){
			// if level 2 display free positions
			$facadeProject = new \App\Facade\Project\TeamFacade($this->em);
			$freePositions = $facadeProject->findFreeUniqueProjectRolesForProjectArray($project->id);
		}
		
 		$projectRoles = implode(' ',$freePositions);
 		
 		$doc = new \Zend_Search_Lucene_Document();
 		$doc->addField(\Zend_Search_Lucene_Field::text('project_id', $project->id));
 		$doc->addField(\Zend_Search_Lucene_Field::text('title', $project->title,'utf-8'));
 		$doc->addField(\Zend_Search_Lucene_Field::unStored('content', $project->content,'utf-8'));
 		$doc->addField(\Zend_Search_Lucene_Field::text('project_roles', $projectRoles,'utf-8'));
 		$doc->addField(\Zend_Search_Lucene_Field::text('priority', $project->priority,'utf-8'));
 		$doc->addField(\Zend_Search_Lucene_Field::text('level', $project->level,'utf-8'));
 			
 		$index->addDocument($doc);
	
	}
	
	
	
	public function addUserIndex($user){
		// open index
		$index = \Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/members');
		
		$specificRoles = "";
		// sort array alfabetically
		if( count($user->getSpecificRolesArray()) > 0 ){
			$specificRoles = $user->getSpecificRolesArray();
			sort($specificRoles);
			$specificRoles = implode(' ',$specificRoles);
		};
		echo $specificRoles ." for ".$user->name ." <br>" ;
		
		$projectRoles = implode(' ',$this->findProjectRolesForUser($user->id));
		echo $projectRoles. ' PROJECT ROLES <br> ';
		
		$doc = new \Zend_Search_Lucene_Document();
		$doc->addField(\Zend_Search_Lucene_Field::text('user_id', $user->id));
		$doc->addField(\Zend_Search_Lucene_Field::text('name', $user->name,'utf-8'));
		$doc->addField(\Zend_Search_Lucene_Field::text('specific_roles', $specificRoles,'utf-8'));
		$doc->addField(\Zend_Search_Lucene_Field::text('project_roles', $projectRoles,'utf-8'));
		
		$index->addDocument($doc);
		
	}
	

	/**
	 * Delete index of user 
	 * @param unknown_type $id
	 */
	public function deleteUserIndex($id){
		$index = \Zend_Search_Lucene::open(APPLICATION_PATH . '/indexes/members');
		$query = 'user_id:"'.$id.'"';

		$hits = $index->find($query);
		
		foreach($hits as $h){
			$index->delete($h->id);	
			echo $h->id . ' has been deleted with name '.$h->name;
		}
		
		
	}

	/**
	 * Find member
	 * @param unknown_type $id
	 * @throws \Exception
	 */
	public function findOneUser($id){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $id );
		if($user){
			return $user;
		}else {
			throw new \Exception("This user doesn't exists");	
		}
	
	}
	
	/**
	 * Find Project Roles for member
	 * @param unknown_type $id
	 */
	public function findProjectRolesForUser($user_id){
		
		$user = $this->findOneUser($user_id);
		
		$stmt = 'SELECT p FROM App\Entity\ProjectRole p WHERE p.type = ?1 AND p.user = ?2 GROUP BY p.name';
		$stmt .= '';
		
		$query = $this->em->createQuery($stmt);
		$query->setParameter(2, $user_id);
		$query->setParameter(1, \App\Entity\ProjectRole::PROJECT_ROLE_TYPE_CREATOR);
		
		$roles = $query->getResult();
		
		$arr = array();
		foreach($roles as $r){
			$arr[] = $r->name;
		}
		// sort array
		sort($arr);
		return $arr;
	}
	
	
	
	
	

}

?>