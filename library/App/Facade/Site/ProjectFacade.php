<?php
namespace App\Facade\Site;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

/**
 * Public Project Facade
 * @author misteryyy
 *
 */
class ProjectFacade {
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $userFacade;
	public function __construct(\Doctrine\ORM\EntityManager $em){
		
		$this->em = $em;
		$this->userFacade = new \App\Facade\UserFacade($em);
	}
	
	/*
	 * Returns one project by id
	 */
	public function findOneProject($id){
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ( $id );
		if($project){
			return $project;
		}else {
			throw new \Exception("This project doesn't exists");
		}
	
	}
	
	/**
	 * Return all users
	 */
	public function findAllProjectsPaginator($options=array()){
		
			$stmt = 'SELECT p FROM App\Entity\Project p ';
			
			// newest, random, most-viewed, recommended
			if(!empty($options['type'])){
				
				// ch
				switch($options['type']){
					case 'most-viewed':
						$stmt .= 'ORDER BY p.viewCount DESC'; // newest			
						break;	
					case 'recommended':
							$stmt .= 'ORDER BY p.featured DESC'; // if many admins like this project, the project has higher rating
							break;
					case 'random':
								$q = $this->em->createQuery('SELECT x.id FROM \App\Entity\Project x');
								$idss = $q->getArrayResult();
								if($idss == null) break; // jump off if no projects
								$mixArray = array();
								
								foreach($idss as $id){
									$mixArray[] = ' p.id = '.$id['id'];		
								}
								
								shuffle($mixArray); // randomize array
								$mixArray = array_slice($mixArray, 0, 6); // take just part
								
								$qb = $this->em->createQueryBuilder('p');
								
								$stmt .= " WHERE ".implode(" OR ", $mixArray);
								
							
								//$stmt .= " WHERE ". $qb->expr()->in('p.id', $mixArray);

					break;
					
					default:
						$stmt .= 'ORDER BY p.created DESC'; // newest
						break;
					
				}
				
			// else display just projects	
			} else {
				$stmt .= 'ORDER BY p.created DESC'; // newest first when DESC
			}

			// if category	
			$query = $this->em->createQuery($stmt);				
			$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
			$iterator = $paginator->getIterator();
			$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
			return new \Zend_Paginator($adapter);	
	}
	
	/**
	 * Find Category by ID
	 * @param unknown_type $category_id
	 * @throws \Exception
	 */
	public function findCategoryById($category_id){
		// check the category
		$category = $this->em->getRepository ('\App\Entity\ProjectCategory')->findOneById ( $category_id );
		if(!$category){
			throw new \Exception("This category doesn't exits");
		}
		
		return $category;
	}
	
	
	/**
	 * Find Category by Name
	 * @param unknown_type $category_name
	 * @throws \Exception
	 */
	public function findCategoryByName($category_name){
		// check the category
		$category = $this->em->getRepository ('\App\Entity\ProjectCategory')->findOneByName ( $category_name);
		if(!$category){
			throw new \Exception("This category doesn't exits");
		}
	
		return $category;
	}
	
	
	/**
	 * Find projects by category
	 * @param unknown_type $category_id
	 * @param unknown_type $options
	 * @throws \Exception
	 */
	public function findAllProjectsByCategory($category_id,$options= array()){
		
		$category = $this->findCategoryById($category_id);
		
		$stmt = 'SELECT p FROM App\Entity\Project p WHERE p.category = ?1';
		$stmt .= 'ORDER BY p.created DESC';
			
		// if category
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $category_id);
		
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
		
	}
	
	
	/**
	 * Find similar projects
	 * @param unknown_type $category_id
	 * @param unknown_type $options
	 * @throws \Exception
	 */
	public function findSimilarProjectsPaginator($project_id,$category_id,$options= array()){
	
	
		$category = $this->findCategoryById($category_id);
	
		$stmt = 'SELECT p FROM App\Entity\Project p WHERE p.category = ?1 AND p.id != ?2 ';
		$stmt .= 'ORDER BY p.created DESC';
			
		// if category
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $category_id);
		$query->setParameter(2, $project_id);
	
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	
	}
	
	public function findProjectForUser($user_id,$project_id){
		// check if user exists
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if($user){
				
			$project = $this->em->getRepository('\App\Entity\Project')->findOneBy(array("user" => $user,"id" => $project_id ));
			if($project == null){
				throw new \Exception("Project for this user doesn't exists");	
			}
			return $project;		
		}
		else {
			throw new \Exception("User doesn't exists");		
		}	
	}
	
	public function findAllProjectsForUser($id){
		// check if user exists
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $id );
	
		if($user){	
			$projects = $this->em->getRepository('\App\Entity\Project')->findBy(array("user" => $user));	
			return $projects;
			
		}else {
			throw new \Exception("User doesn't exists");		
		}	
	}
	
	
	
	/**
	 * Return all categories in array / used for form
	 */
	public function findAllProjectCategoriesArray(){
		$categories = $this->em->getRepository ('\App\Entity\ProjectCategory')->findThemAll();
		$arr = array(); 
		if(count($categories) > 0) {
			foreach ($categories as $cat){	
				$arr[$cat->id] = $cat->name;
			}	
		}
		return $arr;
	}
	
	/**
	 * Return all categories in array / used for form
	 */
	public function findAllProjectCategories(){
		$categories = $this->em->getRepository ('\App\Entity\ProjectCategory')->findThemAll();
		return $categories;
	}

}

?>