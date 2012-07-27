<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;
use Doctrine\ORM\Query\ResultSetMapping;

class SearchEngineFacade {
	
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $facadeUser; 
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		$this->em = $em;
		$this->facadeUser = new \App\Facade\UserFacade($this->em);
		
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
		
		$doc = new \Zend_Search_Lucene_Document();
		$doc->addField(\Zend_Search_Lucene_Field::text('user_id', $user->id));
		$doc->addField(\Zend_Search_Lucene_Field::text('name', $user->name,'utf-8'));
		$doc->addField(\Zend_Search_Lucene_Field::text('specific_roles', $specificRoles,'utf-8'));
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
	
	
	
	

}

?>