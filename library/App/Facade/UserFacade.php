<?php
namespace App\Facade;

use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;
use Doctrine\ORM\Query\ResultSetMapping;

class UserFacade {
	
	/** @var Doctrine\Orm\EntityManager */
	private $em;
	private $facadeNotification;
	
	public function __construct(\Doctrine\ORM\EntityManager $em){
		$this->facadeNotification = new \App\Facade\NotificationFacade($em);
		$this->em = $em;
	}
	
	
	/**
	 * Return all users 
	 */
	public function findAllUsers(){
		$users = $this->em->getRepository ('\App\Entity\User')->findThemAll();
		return $users;
		
	}
	
	/**
	 * Find all friend for user
	 * @param unknown_type $user_id
	 */
	public function findAllFavouriteUsersForUserPaginator($user_id){
		
		
		// checking errors
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		
		$arr= array();
		foreach($user->myFriends as $f){
			$arr[] = $f;
		}
		
		//return new \Zend_Paginator($adapter);
		return \Zend_Paginator::factory($arr);
		
	}
	/**
	 * Start to like member which I like
	 * @param unknown_type $user_id
	 * @param unknown_type $friend_id
	 */
	public function likeMember($user_id,$friend_id){
		
		// thats me and I want add new friend_id
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		} 
		
		$friend = $this->em->getRepository ('\App\Entity\User')->findOneById ( $friend_id);
		if(!$friend){
			throw new \Exception("This member you want add doesn't exists");
		}
		
		$count_of_friends = $friend->getCountFriendsWithMe();
		if($user->isMyFriend($friend)){
			$user->deleteMyFriend($friend); // delete from friends
			$friend->deleteFriendWithMe($user);
			$this->facadeNotification->addUserNotification($user,"Member stopped following ".$friend->getProfileFullUrl(),2);	
			$count_of_friends--;
			$this->em->flush();
			
		} else {
			$user->addNewFriend($friend); // add this friend
			$friend->addFriendWithMe($user);
			$this->facadeNotification->addUserNotification($user,"Member is following ".$friend->getProfileFullUrl(),2);
			$count_of_friends++;
			$this->em->flush();
		}
		
		return array('count_friends' => $count_of_friends);
		
	}
	
	
	
	/**
	 * Return all users
	 */
public function findAllUsersNative($options = array()){
	
	// Equivalent DQL query: "select u from User u where u.name=?1"
	// User owns an association to an Address but the Address is not loaded in the query.
	$rsm = new ResultSetMapping;
	$rsm->addEntityResult('\App\Entity\User', 'u');
	$rsm->addFieldResult('u', 'id', 'id');
	$rsm->addFieldResult('u', 'name', 'name');
	
	$query = $this->em->createNativeQuery('SELECT id, name FROM user WHERE MATCH(name) AGAINST ("josef" IN BOOLEAN MODE) ', $rsm);
	//$query->setParameter(1, 'romanb');
	
	return $query->getResult();
}
	
	/**
	 * Return all users in application, used for search
	 * @param unknown_type $user_id
	 * @param unknown_type $options
	 * @throws \Exception
	 */
	public function findAllUsersPaginator($user_id,$options = array()){
	
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		
		$stmt = 'SELECT u FROM App\Entity\User u';
		//$stmt .= 'ORDER BY a.created, a.roleName DESC';
		$query = $this->em->createQuery($stmt);
		//$query->setParameter(1, $project_id);
			
		$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
		$iterator = $paginator->getIterator();
		$adapter = new \Zend_Paginator_Adapter_Iterator($iterator);
		return new \Zend_Paginator($adapter);
	}
	

	
	/**
	 * Find One User
	 * @param unknown_type $id
	 * @throws \Exception
	 */
	public function findOneUser($user_id){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
		return $user;
	
	}
		
	/**
	 * Create user account
	 * @param unknown_type $data
	 */
	public function createAccount($data = array()){

		$user = new \App\Entity\User();
		$user->setEmail($data['email']);
		$user->setPassword($data['password']);
		$user->setName($data['name']);
		$user->setConfirmed(1); // confirmed // we can find the use for this latter
	
		// setting user info
		$userInfo = new \App\Entity\UserInfo();
		$user->setUserInfo($userInfo);
		
		// find role
		$member  = $this->em->getRepository ('\App\Entity\UserRole')->findOneBy(array("name" => \App\Entity\UserRole::SYSTEM_ROLE_MEMBER));
		$visitor  = $this->em->getRepository ('\App\Entity\UserRole')->findOneBy(array("name" => \App\Entity\UserRole::SYSTEM_ROLE_VISITOR));
		
		$user->addRole($member);
		$user->addRole($visitor);
		
		$this->em->persist($user);
		$this->em->flush();

		// TODO SENDING EMAIL
		// $mailer = new \App\Mailer\HtmlMailer();
		// $mailer->setSubject("Welcome to FLO~ Platform")
		// ->addTo($data['email'])
		// ->setViewParam('name',"Josef Kortan")
		// ->sendHtmlTemplate("welcome.phtml");
			
	}
	
	public function makeTagArray($str){
		$str = trim($str);
		$array = explode(",",$str);
		$array = trimArray($array);
		return $array;
	}
	
	// TODO cant be , after tag list

	
	/**
	 * Return all log information for user
	 * @param unknown_type $user_id
	 * @throws \Exception
	 */
	public function findLogForUser($user_id){
		
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("User doesn't exists");
		}
			
		return $this->em->getRepository ('\App\Entity\UserLog')->findByUser($user);
		
	}
	

	
	
	/**
	 * 
	 * @param unknown_type $id
	 * @param unknown_type $data
	 */
	public function updateSkills($user_id,$data = array()){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("User doesn't exists");
		}
		
			$arrayRoles = array(array("name" => \App\Entity\UserRole::MEMBER_ROLE_STARTER, ),
					array("name" => \App\Entity\UserRole::MEMBER_ROLE_LEADER),
					array("name" => \App\Entity\UserRole::MEMBER_ROLE_BUILDER),
					array("name" => \App\Entity\UserRole::MEMBER_ROLE_GROWER),
					array("name" => \App\Entity\UserRole::MEMBER_ROLE_ADVISER)
			);
	
			foreach($arrayRoles as $role){
				//if specific role is set, add it to the user
				if($data ["role_".$role['name']] == "1" ){                   
					// creating one of the 5 specific roles
					$user->addSpecificRole($role['name']);			
					$specRoleObj = $user->getSpecificRole($role['name']);
							
					// Adding tags
					if( strlen(trim( $data["role_".$role['name']."_tags"] )) >0 ){ // if we have some tags
						$oldTags = $specRoleObj->getTagsArray(); // true for returning array
						$newTags = $this->makeTagArray($data["role_".$role['name']."_tags"]);
						debug(	$newTags);
						$tagsToAdd = array_diff($newTags,$oldTags);
						$tagsToDelete= array_diff($oldTags, $newTags);
						
						//debug("to add");
						//debug($tagsToAdd);
						//debug($tagsToDelete);
							
						
						// adding tags
						foreach($tagsToAdd as $tagAdd){
							
							   $t = $this->em->getRepository("\App\Entity\UserSpecificRoleTag")->findOneBy(array("name"=> $tagAdd));
							    if($t){
							   	$specRoleObj->addTag($t);
								}else{
									// add tag
									$newTag = new \App\Entity\UserSpecificRoleTag();
									$newTag->setName($tagAdd);
									$specRoleObj->addTag($newTag);	
							   	}
						 }
						
						foreach($tagsToDelete as $delTag){
							
							// get tag
							$tagDelObj = $specRoleObj->getTag($delTag);
							if($tagDelObj){
								$specRoleObj->removeTag($tagDelObj);
								// if the tag doesn't have any follower
								if($tagDelObj->getCountOfSpecRolesUsingThisTag() == 0){
									$this->em->remove($tagDelObj);
								}
								
							}		
						}
						
						$this->em->flush();
						
					} else {
						
						// delete all tags for the role
						if($specRoleObj){
						
							// remove tags
							$allTags = $specRoleObj->getTags();
							foreach ($allTags as $removeTag){
								$specRoleObj->removeTag($removeTag);
								if($removeTag->getCountOfSpecRolesUsingThisTag() == 0){
									$this->em->remove($removeTag);
								}
							}
						}
						
						
					}
				
				}else {
					
					$roleObj = $user->getSpecificRole($role['name']);
					if($roleObj){
						
						// remove tags
						$allTags = $roleObj->getTags();	
						foreach ($allTags as $removeTag){
							$roleObj->removeTag($removeTag);
							if($removeTag->getCountOfSpecRolesUsingThisTag() == 0){
									$this->em->remove($removeTag);
							}	
						}

						// remove role
						$user->deleteSpecificRole($roleObj);
						$this->em->remove($roleObj);	
					}			
				}
			
			
			}	
			$this->em->flush();
	
	}
	
	
	/**
	 * Update information about user
	 * @param unknown_type $id
	 * @param unknown_type $data
	 */
	public function updateNotification($user_id,$data = array()){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("User doesn't exists");
		}
		
		$user->emailNewsletter = $data['newsletter'];
		$user->emailNotification = $data['notification'];
		$this->em->flush();
		
	}
		
	/**
	 * Update information about user
	 * @param unknown_type $id
	 * @param unknown_type $data
	 */
	public function updateInfo($user_id,$data = array()){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("User doesn't exists");
		}
			// update basic information in user entity	
			$user->setDescription($data['description']);
			$user->setName($data['name']);
			$user->setCountry($data['country']);
			$user->setDateOfBirthVisibility($data['dateOfBirthVisibility']);
			$user->setEmailVisibility($data['emailVisibility']);
				
			// TODO Parse The date
			$user->setDateOfBirth($data['dateOfBirth']);
				
				// UserInfo already exists just update it
				$info = $user->getUserInfo();
				$info->setSkype($data['skype']);
				$info->setPhone($data['phone']);
				$info->setIm($data['im']);
				$info->setWebsite($data['website']);	
				
				// delete all tags -> empty textfield
				if(strlen(trim($data['fieldOfInterestTag'])) <= 0){
					$userTags = $user->getUserFieldOfInterestTags();
					// Delete previsou tag from the database
					if(!empty($userTags)){
						foreach($userTags as $tag){
								//check if the tag is not the same
								$user->removeUserFieldOfInterestTag($tag);
								// noone else has this tag, delete it from database
								if($tag->getUsers()->count() == 0){
									$this->em->remove($tag); // delete entity tag
								}
						}
					}
					$this->em->flush();
					
					// log
					$this->facadeNotification->addUserNotification($user,"Member updated profile info.",1);
						
				}
				
				
				// user has some tags
				if(strlen(trim($data['fieldOfInterestTag'])) > 0){
				
					$tags = explode(',', $data['fieldOfInterestTag']);
					$tags = trimArray($tags);
					
				// delete all tags before update them
						$userTags = $user->getUserFieldOfInterestTags();				
						// Delete previsou tag from the database
		  				if(!empty($userTags)){
		  				  	foreach($userTags as $tag){			

		  				  		if(!in_array($tag->getName(), $tags)){ //check if the tag is not the same
		  				  		$user->removeUserFieldOfInterestTag($tag);
		 						// noone else has this tag, delete it from database
		 					 	if($tag->getUsers()->count() == 0){
									//echo "Number of users for this tag " . $tag->getUsers()->Count();
									
		 					 		$this->em->remove($tag); // delete entity tag
								}
		 					 	}
		 				  	}	
		 				}
		 	//	$this->em->flush(); // flush because we need to update current tags, when this are deleted
 				
		 		// addTags
 					
 				foreach ($tags as $tag_string){
					$tag = $this->em->getRepository("\App\Entity\UserFieldOfInterestTag")->findOneBy(array("name"=> $tag_string));
					if($tag){
						echo $tag->getName();
						//$user->addUserTag($tag);
					}else {
						$tagObj = new \App\Entity\UserFieldOfInterestTag();
						$tagObj->setName($tag_string);
						$user->addUserFieldOfInterestTag($tagObj);
					}	
				}	
				
				$this->em->flush();
				
				}	
	
		
	}
	
	/**
	 * Update picture and create new resolution for picture thumnail
	 * @param unknown_type user_idd
	 * @param unknown_type user_idath
	 */
	public function updateProfilePicture($user_id,$file){
		$user = $this->em->getRepository ('\App\Entity\User')->findOneById ( $user_id );
		if(!$user){
			throw new \Exception("Member doesn't exists");
		}
			
		$user->setProfilePicture($file);
		$this->em->flush();	
		$this->facadeNotification->addUserNotification($user,"Member changed Profile Picture.",1);	
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