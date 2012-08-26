<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

class ProjectFacade {
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $userFacade;
	private $taskFacade;
	private $aclFacade;
	private $facadeNotification;
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		
		$this->em = $em;
		$this->userFacade = new \App\Facade\UserFacade($em);
		$this->taskFacade = new \App\Facade\Project\TaskFacade($em);
		$this->aclFacade = new \App\Facade\ACLFacade($em);
		$this->facadeNotification = new \App\Facade\NotificationFacade($em);
		
	}
	

	/**
	 * Return mix of projects of my friend
	 * @param unknown_type $user_id
	 * @param unknown_type $options
	 */
	public function findProjectsFromMyFriendPaginator($user_id,$options = array()){

		// find user
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		// if not, just take some projects from all
				
		// find all projects from his friends
		if(count($user->myFriends) > 0){
				
			foreach($user->myFriends as $f){
				$ids[] = $f->id;
			}
			
			$qb = $this->em->createQueryBuilder('u');
			$stmt = "SELECT p FROM App\Entity\Project p JOIN p.user u WHERE ". $qb->expr()->in('u.id', $ids);	
		} else {
			$stmt = "SELECT p FROM App\Entity\Project p JOIN p.user u WHERE u.id != $user_id ";
		}
		
		$stmt .= ' ORDER BY p.created,p.title ASC';
		$query = $this->em->createQuery($stmt);
			
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
		
	}

	
	public function findFeaturedProjectsPaginator($user_id,$options = array()){
	
		// find user
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
	
			$qb = $this->em->createQueryBuilder('u');
			$stmt = "SELECT p FROM App\Entity\Project p  WHERE p.user != ?1 AND p.featured > 0";
			$stmt .= ' ORDER BY p.featured,p.created DESC';
		
	
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $user_id);

		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	}
	
	
	public function disableProjectWidget($user_id,$project_id,$data){
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ($project_id);
		if(!$project){
			throw new \Exception("Can't find this project.");
		}
		
		if($project->user == $user){
			$project->setDisableRoleWidget($data['role_widget_disable']);
			$this->em->flush();
		} else {
			throw new \Exception("You are not allowed to change this property.");
		}
		
		
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
	 * Find Category by ID
	 * @param unknown_type $category_id
	 * @throws \Exception
	 */
	public function findCategoryById($category_id){
		// check the category
		$category = $this->em->getRepository ('\App\Entity\Category')->findOneById ( $category_id );
		if(!$category){
			throw new \Exception("This category doesn't exits");
		}
	
		return $category;
	}
	
	

	
	/**
	 * Return all log information for user
	 * @param unknown_type $project_id
	 * @throws \Exception
	 */
	public function findLogForProject($project_id){
	
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ($project_id);
		if($project){
			return $this->em->getRepository ('\App\Entity\ProjectLog')->findByProject($project);
		}
		else{
			throw new \Exception("Can't find this project.");
		}
	}
	
	
 	/**
 	 * Handle new picture for the project. Delete the old files if its neccessary
 	 * @param unknown_type $user_id
 	 * @param unknown_type $project_id
 	 * @param unknown_type $path
 	 * @param unknown_type $fileName
 	 * @throws \Exception
 	 */
	public function updateProjectPicture($user_id,$project_id,$filename){
		
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneBy(array("id" => $project_id,"user" => $user_id));
		if(!$project){
			throw new \Exception("Can't find this project for this user.");
		}	
		
		$project->setPicture($filename);
		$this->em->flush(); // save to db
		$this->facadeNotification->addProjectNotification($project, "Updated project picture",2);
		
	}
	
	/**
	 * Create basic project for user
	 * @param unknown_type $id
	 * @param unknown_type $dataFirstStep
	 * @param unknown_type $dataSecondStep
	 */
	public function createProject($id,$dataFirstStep = array(),$dataSecondStep = array(),$dataThirdStep = array(),$dataFourthStep = array()){
			
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
				// find category
				$category = $this->em->getRepository ('\App\Entity\Category')->findOneBy(array("id"=> $dataFirstStep['category']));
				if($category){
				$newProject = new \App\Entity\Project($user, $category, 
													$dataFirstStep['title'], 
													$dataFirstStep['pitch'],
													$dataFirstStep['content'],
													$dataFirstStep['priority']);

				$newProject->setLesson($dataFirstStep['plan']);
				$newProject->setIssue($dataFirstStep['issue']);
				$newProject->setPlan($dataFirstStep['lesson']);

				$this->em->persist($newProject);
			
				// adding tags
				
				$tags_raw = $dataFirstStep['project_tags'];
				$clear_tags = parseTagsToArray($tags_raw);
				
						foreach($clear_tags as $tag){
							
							$tagObj = $this->em->getRepository ('\App\Entity\ProjectTag')->findOneBy(array("name"=> $tag));
							// exists, just add to the project
							if($tagObj){
								$newProject->addTag($tagObj);
							}else {
								// create new tag
								$tagNew = new \App\Entity\ProjectTag($tag);
								$newProject->addTag($tagNew);
							}
								
						}
				$this->em->flush(); // save to db
				
				// create dir for project
				$fileManager = new \Boilerplate_Util_FileManagerS3($newProject);
				$dir = $fileManager->createDirForProject($newProject->user->id);	
				$newProject->setDir($dir);

				// create thumbnails
				if(isset($dataSecondStep)){
					$info = $fileManager->createThumbnailsForProject($newProject->user->id,$dataSecondStep['absolutPath'],$dataSecondStep['fileName'],$dir);
					$newProject->setPicture($info['file']);
					$this->em->flush();
				}
				
				// creating roles for creator
				$arrayRoles = array(array("name" => \App\Entity\UserRole::MEMBER_ROLE_STARTER, ),
						array("name" => \App\Entity\UserRole::MEMBER_ROLE_LEADER),
						array("name" => \App\Entity\UserRole::MEMBER_ROLE_BUILDER),
						array("name" => \App\Entity\UserRole::MEMBER_ROLE_GROWER),
						array("name" => \App\Entity\UserRole::MEMBER_ROLE_ADVISER)
				);
				
				foreach($arrayRoles as $role){
					if($dataThirdStep['role_'.$role['name']] == 1 ){
						
						$newRole = new \App\Entity\ProjectRole($role['name'], \App\Entity\ProjectRole::PROJECT_ROLE_TYPE_CREATOR);
						$user->addProjectRole($newRole);
						$newProject->addProjectRole($newRole);
					}
				}
				
				$this->em->flush();
				
				// disable role widget and adding question
				if($dataThirdStep['role_widget_disable'] == 1){
					$newProject->setDisableRoleWidget(true);
				} else {
					// no change + create questions for new members
					for($i = 1; $i < 6; $i++){
						$q =$dataThirdStep['question_'.$i]; 					 
						if(strlen (trim($q)) > 0){
					 		$newQuestion = new \App\Entity\ProjectRoleWidgetQuestion($q);
					 		$newProject->addRoleWidgetQuestion($newQuestion);
					 	}
					}
				}
				
				$this->em->flush();
				
				// survey adding
				foreach($dataFourthStep as $key => $value){
					$id = substr(strrchr($key, '_'), 1);
					$question = $this->em->getRepository ('\App\Entity\ProjectSurveyQuestion')->findOneBy(array("id"=> $id));
					$newAnswer = new \App\Entity\ProjectSurveyAnswer($value,$newProject);
					$newAnswer->setQuestion($question);
				}
				$this->em->flush();
				// log
				$this->facadeNotification->addUserNotification($user,"Member published new project ".$newProject->getProjectFullUrl(),5);		
					
				} else {	
					throw new \Exception("Category doesn't exists");
				}
					
			
		}
		
	public function	updateProject($user_id,$project_id,$data = array()){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		
			$project = $this->em->getRepository('\App\Entity\Project')->findOneBy(array("user" => $user,"id" => $project_id ));
			if($project == null){
				throw new \Exception("Project for this user doesn't exists");
					
			}

			$category = $this->em->getRepository ('\App\Entity\Category')->findOneBy(array("id"=> $data['category']));
			if($category){
				
				$project->setContent($data['content']);
				$project->setPitchSentence($data['pitch']);
				$project->setPriority($data['priority']);
				$project->setTitle($data['title']);
				$project->setCategory($category);
				$project->setLesson($data['plan']);
				$project->setIssue($data['issue']);
				$project->setPlan($data['lesson']);
				$project->setModified();
				
				// tags modification
				$oldTags = $project->getTagsArray();
				$newTags = parseTagsToArray($data['project_tags']);
				
				
				$tagsToAdd = array_diff($newTags,$oldTags);
				$tagsToDelete= array_diff($oldTags, $newTags);
				
				//debug("to add");
				//debug($tagsToAdd);
				//debug($tagsToDelete);
							
				// adding tags
				foreach($tagsToAdd as $tagAdd){			
					$t = $this->em->getRepository("\App\Entity\ProjectTag")->findOneBy(array("name"=> $tagAdd));
					if($t){
						$project->addTag($t);
					}else{
						// add tag
						$newTag = new \App\Entity\ProjectTag($tagAdd);
						$project->addTag($newTag);
					}
				}
				
				foreach($tagsToDelete as $delTag){	
 					// get tag
					$tagDelObj = $project->getTag($delTag);
 					if($tagDelObj){
 						$project->removeTag($tagDelObj);
						// if the tag doesn't have any follower
 						if($tagDelObj->getCountOfProjects() == 0){
 							$this->em->remove($tagDelObj);
						}
					}
 				}
				
				$this->em->flush();
				$this->facadeNotification->addProjectNotification($project, "Project has updated description",2);
			}else {
				throw new \Exception("Category  doesn't exists");
				
			}			
	
	}
	/**
	 * Adds creator roles
	 * @param unknown_type $id_project
	 * @param unknown_type $data
	 */
	public function addCreatorRoleToProject($id_project,$data = array()){
		
	}
	
	/**
	 * Return all users
	 */
	public function findAllProjects(){
		$projects = $this->em->getRepository ('\App\Entity\Project')->findThemAll();
		return $projects;
	
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
	
	
	
	
	/**
	 * Find projects by category
	 * @param unknown_type $category_id
	 * @param unknown_type $options
	 * @throws \Exception
	 */
	public function findAllProjectsByCategoryPaginator($category_id,$options= array()){
	
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
	
	
	public function findAllProjectsForUser($user_id,$options = array()){
		
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		 
		$stmt = 'SELECT p FROM App\Entity\Project p WHERE p.user = ?1';
		$stmt .= 'ORDER BY p.created DESC';

		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $user_id);
		
		return $query->getResult();	
	}
	
	public function findAllProjectsForUserPaginator($user_id,$options = array()){
	
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		$stmt = 'SELECT p FROM App\Entity\Project p WHERE p.user = ?1';
		$stmt .= 'ORDER BY p.created DESC';
	
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $user_id);
		
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		
		$iterator = $paginator->getIterator();
		
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);

	}

	public function findAllFavouriteProjectsForUserPaginator($user_id,$options=array()){
		
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		
		
		$stmt = 'SELECT p FROM App\Entity\Project p WHERE p.user = ?1';
		//$stmt .= 'ORDER BY p.created DESC';
		
		$query = $this->em->createQuery($stmt);
		$query->setParameter(1, $user_id);
		
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		
		
		// TODO better way to fix this
		$iterator = $paginator->getIterator();
		
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		
		$arr= array();
		foreach($user->favouriteProjects as $p){
			$arr[] = $p;
		}
		
		//return new \Zend_Paginator($adapter);
		return \Zend_Paginator::factory($arr); 
		
	}

	/**
	 * Increment viewCount for the project
	 * @param unknown_type $project_id
	 * @throws \Exception
	 */
	public function addView($project_id){
		
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ( $project_id);
		if(!$project){
			throw new \Exception("This project you trying find doesn't exists");
		}
		$project->viewCount ++;
		$this->em->flush(); // save the view count
	}
	
	/**
	 * Start to like project 
	 * @param unknown_type $user_id
	 * @param unknown_type $project_id
	 */
	public function likeProject($user_id,$project_id){
	
		// thats me and I want add new friend_id
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
	
		$project = $this->em->getRepository ('\App\Entity\Project')->findOneById ( $project_id);
		if(!$project){
			throw new \Exception("This project you want add doesn't exists");
		}
	
		$countOfFollowers = $project->getCountFollowers();
		
		if($user->isMyFavouriteProject($project)){
			
			// if is admin give project high priority
			if($this->aclFacade->isAdmin($user_id)){
			 	$project->featured--;	
			}
			
			$user->deleteMyFavouriteProject($project); // delete from my projects
			$countOfFollowers--;
			$this->facadeNotification->addUserNotification($user,"Member stopped following project ".$project->getProjectFullUrl(),2);
			
			$this->em->flush();
				
		} else {
			$user->addNewFavouriteProject($project); // add this project
			
			// if is admin give project high priority
			if($this->aclFacade->isAdmin($user_id)){
				$project->featured++;
			}
			$this->facadeNotification->addUserNotification($user,"Member is following project ".$project->getProjectFullUrl(),2);
			
			$countOfFollowers++;
			$this->em->flush();
		}
	
		return array('count_followers' => $countOfFollowers);
	
	}
	
	
	
	
	/**
	 * Return all categories in array / used for form
	 */
	public function findAllProjectCategoriesArray(){
		$categories = $this->em->getRepository ('\App\Entity\Category')->findThemAll();
		$arr = array(); 
		if(count($categories) > 0) {
			foreach ($categories as $cat){	
				$arr[$cat->id] = $cat->name;
			}	
		}
		return $arr;
	}
	

	
}

?>