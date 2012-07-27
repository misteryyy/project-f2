<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;
use Doctrine\ORM\Query\ResultSetMapping;

class SearchEngineFacade {
	
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		
		$this->em = $em;
	}

	
	/**
	 * Build start index form member  
	 * @return number of non deleted index in this search engine
	 */
	public function buildMemberIndexes(){
	
		$facadeUser = new \App\Facade\UserFacade($this->em);
		$paginator = $facadeUser->findAllUsersPaginator($this->_member_id,$this->_request->getParams());
			
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
		$doc->addField(\Zend_Search_Lucene_Field::text('id', $user->id));
		$doc->addField(\Zend_Search_Lucene_Field::text('name', $user->name,'utf-8'));
		$doc->addField(\Zend_Search_Lucene_Field::text('specific_roles', $specificRoles,'utf-8'));
		$index->addDocument($doc);
		
	}
	

	/**
	 * 
	 * @param unknown_type $id
	 */
	public function deleteUserIndex($id){
		
		
		
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