<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\User")
 * @Table(name="user",indexes={@index(name="search_user_email",columns={"email"})})
 */
class User {

	const PROFILE_PHOTO_RESOLUTION_TINY = 'tiny';
	const PROFILE_PHOTO_RESOLUTION_TINY_WIDTH = 25;
	const PROFILE_PHOTO_RESOLUTION_TINY_HEIGHT = 25;
	
	const PROFILE_PHOTO_RESOLUTION_SMALL = 'small';
	const PROFILE_PHOTO_RESOLUTION_SMALL_WIDTH = 50;
	const PROFILE_PHOTO_RESOLUTION_SMALL_HEIGHT = 50;
	
	const PROFILE_PHOTO_RESOLUTION_MEDIUM = 'medium';
	const PROFILE_PHOTO_RESOLUTION_MEDIUM_WIDTH = 100;
	const PROFILE_PHOTO_RESOLUTION_MEDIUM_HEIGHT = 100;
	
	const PROFILE_PHOTO_RESOLUTION_BIG = 'big';
	const PROFILE_PHOTO_RESOLUTION_BIG_WIDTH = 120;
	const PROFILE_PHOTO_RESOLUTION_BIG_HEIGHT = 120;
	
	const PROFILE_PHOTO_RESOLUTION_LARGE = 'large';
	const PROFILE_PHOTO_RESOLUTION_LARGE_WIDTH = 167;
	const PROFILE_PHOTO_RESOLUTION_LARGE_HEIGHT = 167;
	
	/**
	 * @Id @Column(type="integer", name="id")
	 * @GeneratedValue
	 */
	private $id;
	
	/**
	 * @ManyToMany(targetEntity="UserRole", inversedBy="user_role",cascade={"persist","remove"})
	 * @JoinTable(name="user_has_user_role",
	 * joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 * inverseJoinColumns={@JoinColumn(name="user_role_id",referencedColumnName="id")})
	 */
	private $roles; // every user is in role / System Roles, Benefit roles
	
	/**
	 * @OneToMany(targetEntity="UserSpecificRole", mappedBy="user", cascade={"ALL"})
	 */
	private $specRoles;
	
	/**
	 * @OneToMany(targetEntity="ProjectRole", mappedBy="user", cascade={"ALL"})
	 */
	private $projectRoles;
	

	/**
	 * @Column(type="string", name="name")
	 */
	private $name;
	/**
	 * @Column(type="string", name="email",unique=true)
	 */
	private $email;
	
	/**
	 * @Column(type="string", name="profile_picture",unique=true,nullable=true)
	 */
	private $profilePicture;
		
	/**
	 * @Column(type="boolean", name="email_visibility",nullable=true)
	 */
	private $emailVisibility;
	
	
	/**
	 * @Column(type="boolean", name="email_newsletter")
	 */
	private $emailNewsletter;
	
	/**
	 * @Column(type="boolean", name="email_notification")
	 */
	private $emailNotification;
	
	/**
	 * @Column(type="string", name="password")
	 */
	private $password;
	/**
	 * @Column(type="string", name="country", columnDefinition="CHAR(2)",nullable=true)
	 */
	protected $country;
	
	/** @Column(type="smallint",name="confirmed",nullable=true) */
	private $confirmed;
	/**
	 * @OneToOne(targetEntity="UserInfo",cascade={"persist"})
	 * @JoinColumn(name="user_info_id", referencedColumnName="id")
	 */
	private $userInfo;
	/**
	 * @Column(type="string", name="description",nullable=true)
	 */
	private $description;
	/**
	 * @column(type="date",name="date_of_birth",nullable=true)
	 */
	public $dateOfBirth;
	
	/**
	 * @column(type="datetime",name="created",nullable=true)
	 */
	public $created;
	
	/**
	 * @column(type="datetime",name="last_login",nullable=true)
	 */
	public $lastLogin;
	
	/**
	 * @Column(type="boolean", name="date_of_birth_visibility",nullable=true)
	 */
	private $dateOfBirthVisibility;
	
	/**
	 * @Column(type="boolean", name="ban")
	 */
	private $ban;
	
	
	/**
	 *
	 * @param $property \Doctrine\Common\Collections\Collection
	 *       	 @OneToMany(targetEntity="Project",mappedBy="user",
	 *        	cascade={"persist","remove"})
	 */
	private $projects;
	
	/**
	 * @ManyToMany(targetEntity="UserFieldOfInterestTag", inversedBy="users",cascade={"persist","remove"})
	 * @JoinTable(name="user_has_user_field_of_interest_tag",
	 * joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 * inverseJoinColumns={@JoinColumn(name="user_field_of_interest_tag_id",referencedColumnName="id")})
	 */
	private $userFieldOfInterestTags;
	
	
	/**
	 * @ManyToMany(targetEntity="User", mappedBy="myFriends")
	 */
	private $friendsWithMe;
	
	
	/**
	 * @ManyToMany(targetEntity="Project", inversedBy="favouriteProjects")
	 * @JoinTable(name="user_has_favourite_project",
	 *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="project_id", referencedColumnName="id")}
	 *      )
	 */
	private $favouriteProjects;
	
	
	
	/**
	 * @ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
	 * @JoinTable(name="user_has_friend",
	 *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@JoinColumn(name="friend_user_id", referencedColumnName="id")}
	 *      )
	 */
	private $myFriends;
	


	public function __construct() {
		$this->friendsWithMe = new \Doctrine\Common\Collections\ArrayCollection();
		$this->favouriteProjects = new \Doctrine\Common\Collections\ArrayCollection();
		$this->myFriends = new \Doctrine\Common\Collections\ArrayCollection();
		$this->userFieldOfInterestTags = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->specRoles = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->projectRoles = new \Doctrine\Common\Collections\ArrayCollection ();	
		$this->floMessages = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->projects = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->emailVisibility = false;
		$this->dateOfBirthVisibility = false;
		$this->confirmed = true;
		$this->dateOfBirth = new \DateTime();
		$this->created = new \DateTime("now");
		$this->lastLogin = new \DateTime("now");
		$this->ban = false;
		$this->info = new \App\Entity\UserInfo();
		
	
		// notification settings
		$this->emailNewsletter = true;
		$this->emailNotification = true;
	}
	
	public function setProfilePicture($path){
	
		$this->profilePicture = $path;
	}
	
	/**
	 * @return the $emailNewsletter
	 */
	public function getEmailNewsletter() {
		return $this->emailNewsletter;
	}

	
	
	
	/**
	 * @return the $emailNotification
	 */
	public function getEmailNotification() {
		return $this->emailNotification;
	}

	/**
	 * @param boolean $emailNewsletter
	 */
	public function setEmailNewsletter($emailNewsletter) {
		$this->emailNewsletter = $emailNewsletter;
	}

	/**
	 * @param boolean $emailNotification
	 */
	public function setEmailNotification($emailNotification) {
		$this->emailNotification = $emailNotification;
	}

	/**
	 * Return profile picture for user
	 * Options: large,big,medium,small,tiny
	 * @param unknown_type $size
	 */
	public function getProfilePicture($size = self::PROFILE_PHOTO_RESOLUTION_LARGE) {
	
		if ($this->profilePicture == null) {
			return null;
		}
			
		$arr = array (self::PROFILE_PHOTO_RESOLUTION_LARGE,
				self::PROFILE_PHOTO_RESOLUTION_BIG,
				self::PROFILE_PHOTO_RESOLUTION_SMALL,
				self::PROFILE_PHOTO_RESOLUTION_MEDIUM ,
				self::PROFILE_PHOTO_RESOLUTION_TINY );
	
		if (! in_array ( $size, $arr )) {
			return $this->profilePicture;
		}
	
		$ext = substr ( strrchr ( $this->profilePicture, '.' ), 1 );
		$pre = substr ( $this->profilePicture, 0, strrpos ( $this->profilePicture, '_' ) );
		return $pre . '_' . $size . '.' . $ext;
	}

	/**
	 * Return the whole address for image url
	 * @param unknown_type $size
	 */
	public function getProfilePictureUrl($size = self::PROFILE_PHOTO_RESOLUTION_LARGE){
		
		if($this->getProfilePicture($size) == null) {
			$file =  "no_user_image_" . $size . ".jpg";
			return '/media/slepice-site/1.0.0/img/'.$file;
			
		} else {$file = $this->getProfilePicture($size);}

		$config = new \Zend_Config(\Zend_Registry::get('config'));
		if($config->app->s3->storage->enabled){
			return $config->app->s3->storage->web_url.'users/'.$file;
		}
			
		return '/storage/users/'.$file;
	
	}

		
	/**
	 * Return all specific roles
	 */
	public function getSpecificRoles(){
		return $this->specRoles;
	}
	
	/**
	 * Delete specific role with all their tags
	 * @param unknown_type $name
	 */
	public function deleteSpecificRole($role){
	
		$role->setUser(null);
		$this->specRoles->removeElement($role);
	}
	
	/**
	 * Return array of specific roles who has this member
	 */
	public function getSpecificRolesArray(){
		$arr = array();
		
		foreach($this->specRoles as $r){
			$arr[] = $r->name;
		}
		
		return $arr;
	}

	/**
	 * @return the $role
	 */
	public function getRoles() {
		return $this->roles;
	}
	
	public function getRolesArray(){
		$r = array();
		foreach ($this->roles as $role){
			$r[] = $role->getName();
		}		
		return $r;
	}
	
	/**
	 * @param number $role
	 */
	public function addRole($role) {
		$role->addUser($this);
		$this->roles[] = $role;
	}
	
	public function addProjectRole($role){
		$role->setUser($this);
		$this->projectRoles[] = $role;
	}
	
	public function getId(){
		return $this->id;
	}

	/**
	 * Add new Specific Role
	 *
	 */
	public function addSpecificRole($name)
	{
		// add only if don't have this role
		if(!$this->getSpecificRole($name)){
			$this->specRoles[] = new \App\Entity\UserSpecificRole($name, $this);
		}
	}
	/**
	 * Return specific role on key index
	 */
	public function getSpecificRole($name){
			
		if($this->specRoles->count() > 0){
			foreach($this->specRoles as $role){
				if($role->getName() == $name){
					return $role;
				}
			}
		}
		return false;
	
	}
	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection $user_tags
	 */
	public function addUserFieldOfInterestTag($tag) {
	
		$this->userFieldOfInterestTags[] = $tag;
		$tag->addUser($this); // synchronously updating inverse side
	}
	
	/**
	 * Returns user tags in format for form
	 */
	public function getUserFieldOfInterestTagsString(){	
		
		if($this->userFieldOfInterestTags->isEmpty()){
			
			return "";
		}
		
		if(!empty( $this->userFieldOfInterestTags)){
			
			foreach ($this->userFieldOfInterestTags as $tag){	
				$tags [] = $tag->getName();
			}	
			$implode = implode(',', $tags);
			return $implode;
		}
	}
	
	/**
	 * @return the $user_tags
	 */
	public function getUserFieldOfInterestTags() {
		return $this->userFieldOfInterestTags;
	}
	
	
	
	public function removeUserFieldOfInterestTag($userTag){
	
		$this->userFieldOfInterestTags->removeElement($userTag);
		$userTag->removeUser($this);
	}
	
	
	public function setPassword($value) {
		$this->password = sha1 ( $value );
	}
	
	
	/**
	 * @return the $emailVisibility
	 */
	public function getEmailVisibility() {
		return $this->emailVisibility;
	}

	/**
	 * @return the $country
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @return the $confirmed
	 */
	public function getConfirmed() {
		return $this->confirmed;
	}

	/**
	 * @return the $description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return the $dateOfBirth
	 */
	public function getDateOfBirth() {
		return $this->dateOfBirth;
	}

	/**
	 * @return the $dateOfBirthVisibility
	 */
	public function getDateOfBirthVisibility() {
		return $this->dateOfBirthVisibility;
	}

	/**
	 * @return the $projects
	 */
	public function getProjects() {
		return $this->projects;
	}



	/**
	 * @param field_type $emailVisibility
	 */
	public function setEmailVisibility($emailVisibility) {
		$this->emailVisibility = $emailVisibility;
	}

	/**
	 * @param field_type $country
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * @param field_type $confirmed
	 */
	public function setConfirmed($confirmed) {
		$this->confirmed = $confirmed;
	}

	/**
	 * @param field_type $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @param field_type $dateOfBirth
	 */
	public function setDateOfBirth($dateOfBirth) {
		$date = explode("/", $dateOfBirth);
		$datetime = new \DateTime();
		$datetime->setDate($date[0], $date[1], $date[2]);
		// set the datetime
		$this->dateOfBirth =  $datetime;
	}

	/**
	 * @param field_type $dateOfBirthVisibility
	 */
	public function setDateOfBirthVisibility($dateOfBirthVisibility) {
		$this->dateOfBirthVisibility = $dateOfBirthVisibility;
	}

	/**
	 * @param field_type $projects
	 */
	public function setProjects($projects) {
		$this->projects = $projects;
	}

	

	public function getPassword() {
		return $this->password;
	}
	
	public function setName($value) {
		$this->name =  $value ;
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function setEmail($value) {
		$this->email =  $value ;
	}
	
	
	public function getEmail() {
		return $this->email;
	}
	
	public function getUserInfo(){
		
		return $this->userInfo;
	}
	
	/**
	 * Profile Public Url
	 */
	public function getProfileUrl(){
		
		return '/member/profile/index/id/'.$this->id;
	}
	
	/**
	 * Return users profile full url in format <a href="url">Name</a>
	 */
	public function getProfileFullUrl(){
		return '<a href="'.$this->getProfileUrl().'">'.$this->name."</a>";
	}
	
	
	/**
	 * Get count member who likes
	 */
	public function getCountMyFriends(){
		return $this->myFriends->count();
	}
	
	/**
	 * Add new friend
	 * @param unknown_type $friend
	 */
	public function addNewFriend($friend){
		$this->myFriends->add($friend);
	}
	
	/**
	 * Add new favourite project
	 * @param unknown_type $project
	 */
	public function addNewFavouriteProject($project){
		$this->favouriteProjects->add($project);
	}
	
	public function addFriendWithMe($friend){
		$this->friendsWithMe->add($friend);
	}
	
	public function deleteMyFriend($friend){
		$this->myFriends->removeElement($friend);
	}
	
	public function deleteMyFavouriteProject($project){
		$this->favouriteProjects->removeElement($project);
	}
	
	
	
	
	public function deleteFriendWithMe($friend){
		$this->friendsWithMe->removeElement($friend);
	}
	

	/**
	 * Return if this friend is my friend
	 * @param unknown_type $friend
	 */
	public function isMyFriend($friend){
		return $this->myFriends->contains($friend);
	}
	
	/**
	 * Return if this project is my favourite
	 * @param unknown_type $friend
	 */
	public function isMyFavouriteProject($project){
		return $this->favouriteProjects->contains($project);
	}
	
	
	/**
	 * Return if this friend is my friend
	 * @param unknown_type $friend
	 */
	public function isFriendWithMe($friend){
		return $this->friendsWithMe->contains($friend);
	}
	
	
	/**
	 * Get count member who likes
	 */
	public function getCountFriendsWithMe(){
		return $this->friendsWithMe->count();
	}
	
	
	/**
	 * Get count of favourite projects
	 */
	public function getCountFavouriteProjects(){
		return $this->favouriteProjects->count();
	}
	
	
	public function setUserInfo(\App\Entity\UserInfo $info){
		$this->userInfo = $info;
	}
	
	public function __get($property) {
		// If a method exists to get the property call it.
		if (method_exists ( $this, 'get' . ucfirst ( $property ) )) {
			// This will call $this->getPassword() while getting $this->password
			return call_user_func ( array ($this, 'get' . ucfirst ( $property ) ) );
		} else {
			return $this->$property;
			
		}
	}
	
	public function __set($property, $value) {
		// If a method exists to set the property call it.
		if (method_exists ( $this, 'set' . ucfirst ( $property ) )) {
			// This will call $this->setPassword($value) while setting
			// $this->password
			return call_user_func ( array ($this, 'set' . ucfirst ( $property ) ), $value );
		} else {
			$this->$property = $value;
		}
	}
	
	/**
	 * Return just basic information about the entity
	 */
	public function toArray(){
		$params = array ("id" => $this->id,
				'name' => $this->name,
				"count_friends" => $this->getCountMyFriends(),
				"profile_picture_large" => $this->getProfilePictureUrl("large"),
				"profile_picture_medium" => $this->getProfilePictureUrl("medium"),
				"profile_picture_small" => $this->getProfilePictureUrl("small"),
				);
		return $params;
	}
}
    
   