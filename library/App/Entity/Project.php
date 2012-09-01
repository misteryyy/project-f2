<?php
namespace App\Entity;
/**
 * @Entity(repositoryClass="App\Repository\Project")
 * @Table(name="project")
 */
class Project {
	const LEVEL_1 = 1;
	const LEVEL_2 = 2;
	const LEVEL_3 = 3;
	const PROJECT_PHOTO_RESOLUTION_TINY = 'tiny';
	const PROJECT_PHOTO_RESOLUTION_TINY_WIDTH = 102;
	const PROJECT_PHOTO_RESOLUTION_TINY_HEIGHT = 70;
	
	const PROJECT_PHOTO_RESOLUTION_SMALL = 'small';
	const PROJECT_PHOTO_RESOLUTION_SMALL_WIDTH = 140;
	const PROJECT_PHOTO_RESOLUTION_SMALL_HEIGHT = 97;
	
	const PROJECT_PHOTO_RESOLUTION_MEDIUM = 'medium';
	const PROJECT_PHOTO_RESOLUTION_MEDIUM_WIDTH = 265;
	const PROJECT_PHOTO_RESOLUTION_MEDIUM_HEIGHT = 180;
	
	const PROJECT_PHOTO_RESOLUTION_BIG = 'big';
	const PROJECT_PHOTO_RESOLUTION_BIG_WIDTH = 500;
	const PROJECT_PHOTO_RESOLUTION_BIG_HEIGHT = 360;
	
	const PROJECT_PHOTO_RESOLUTION_LARGE = 'large';
	const PROJECT_PHOTO_RESOLUTION_LARGE_WIDTH = 655;
	const PROJECT_PHOTO_RESOLUTION_LARGE_HEIGHT = 400;
	
	/**
	 * @Id @Column(type="integer", name="id")
	 * @GeneratedValue
	 */
	private $id;
	/**
	 * @Column(type="string", name="title")
	 */
	private $title;
	
	/**
	 * @Column(type="text", name="content")
	 */
	private $content;
	/**
	 * @Column(type="string", name="pitch_sentence")
	 */
	private $pitchSentence;
	
	/**
	 * @OneToMany(targetEntity="ProjectRole",
	 * mappedBy="project",cascade={"persist","remove"})
	 */
	private $roles;
	

	/**
	 * @Column(type="boolean", name="disableRoleWidget")
	 */
	private $disableRoleWidget;
	
	/**
	 * @OneToMany(targetEntity="ProjectRoleWidgetQuestion",
	 * mappedBy="project",cascade={"persist","remove"})
	 */
	private $roleWidgetQuestions;
	
	/**
	 * @Column(type="string", name="dir",nullable=true)
	 */
	private $dir;
	
	/**
	 * @Column(type="text", name="issue",nullable=true)
	 */
	private $issue;
	/**
	 * @Column(type="text", name="lesson",nullable=true)
	 */
	private $lesson;
	/**
	 * @Column(type="text", name="plan",nullable=true)
	 */
	private $plan;
	
	/**
	 * @ManyToMany(targetEntity="User", mappedBy="favouriteProjects")
	 */
	private $followers;
		
	/**
	 * @Column(type="datetime",name="created")
	 */
	private $created;
	
	/**
	 * @Column(type="integer", name="view_count")
	 */
	private $viewCount;
	
	/**
	 * @Column(type="string", name="picture",nullable=true)
	 */
	private $picture;
	
	/**
	 * @Column(type="boolean", name="ban")
	 */
	private $ban;
	
	
	/**
	 * @Column(type="integer", name="featured")
	 */
	private $featured;
	
	
	/**
	 * @Column(type="integer", name="priority")
	 */
	private $priority;
	
	/**
	 * @Column(type="integer")
	 */
	private $level;
	
	/**
	 * @column(type="datetime",nullable=true)
	 */
	public $modified;
	
	/**
	 * @prePersist
	 * @preUpdatet
	 */
	public function update() {
		$this->modified = new \DateTime ( 'now' );
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	/**
	 * @manyToOne(targetEntity="Category", inversedBy="projects")
	 * @joinColumn(name="category_id")
	 */
	private $category;
	
	/**
	 *
	 * @var User @ManyToOne(targetEntity="User")
	 *      @JoinColumns({
	 *      @JoinColumn(name="user_id", referencedColumnName="id")
	 *      })
	 */
	private $user;
	
	/**
	 * @manyToMany(targetEntity="ProjectTag",inversedBy="projects",cascade={"persist","remove"})
	 * @joinTable(name="project_has_project_tag",
	 * joinColumns={@joinColumn(name="project_id", referencedColumnName="id")},
	 * inverseJoinColumns={@joinColumn(name="project_tag_id",
	 * referencedColumnName="id")})
	 */
	private $tags;
	
	public function getCreatorRolesArray() {
		$arr = array ();
		foreach ( $this->roles as $role ) {
			if($role->type == \App\Entity\ProjectRole::PROJECT_ROLE_TYPE_CREATOR){
			$arr [] = $role->getName ();}
		}
		return $arr;
	}
	
	public function setDir($dir) {
		$this->dir = $dir;
	}
	
	public function getId() {
		return $this->id;
	}
	
	public function getUser() {
		return $this->user;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	
	/**
	 * Return true if the user is following this project
	 * @param unknown_type $user
	 */
	public function isFollower($user){
		return $this->followers->contains($user);
	}
	
	public function setLevel($level){
		$this->level = $level;
	}
	
	
	/**
	 * @return the $viewCount
	 */
	public function getViewCount() {
		return $this->viewCount;
	}

	/**
	 * @param number $viewCount
	 */
	public function setViewCount($viewCount) {
		$this->viewCount = $viewCount;
	}

	/*
	 * Add tag to the SpecificRole
	 */
	public function addTag($tag) {
		// add tag only if doesn't exist
		if (! $this->getTag ( $tag->getName () )) {
			// adding role to the tag
			$tag->addProject ( $this );
			$this->tags [] = $tag;
		}
	}
	
	public function addProjectRole($role) {
		$role->setProject ( $this );
		$this->roles [] = $role;
	}
	
	public function addProjectUpdate($update) {
		$update->setProject ( $this );
		$this->projectUpdates [] = $update;
	}
	/*
	 * Add role widget question, note: expected maximum is five
	 */
	public function addRoleWidgetQuestion($q) {
		$q->setProject ( $this );
		$this->roleWidgetQuestions [] = $q;
	}
	
	/**
	 * Return tags in string format with , divider
	 */
	public function getTag($name) {
		foreach ( $this->tags as $tag ) {
			if ($tag->getName () == $name) {
				return $tag;
			}
		}
		return false;
	}
	
	/*
	 * Remove tag from this role
	 */
	public function removeTag($tag) {
		// remove role from the tag, maybe somebody will use this tag
		$tag->removeProject ( $this );
		$this->tags->removeElement ( $tag );
	}
	
	/**
	 * Return tags in string format with , divider
	 */
	public function getTagsString($toString = true) {
		// collecting names
		$tagArray = array ();
		foreach ( $this->tags as $tag ) {
			$tagArray [] = $tag->getName ();
		}
		return implode ( ",", $tagArray );
	}
	
	/**
	 * Function return array of string tags
	 * if no tags are find.
	 * Return empty array
	 */
	public function getTagsArray() {
		$tagArray = array ();
		
		if ($this->tags->count () > 0) {
			foreach ( $this->tags as $tag ) {
				$tagArray [] = $tag->getName ();
			}
		}
		
		return $tagArray;
	}
	
	/**
	 * Return all tags for this project in Doctrine OBJs
	 */
	public function getTags() {
		
		return $this->tags;
	}
	
	
	
	/**
	 * Profile Public Url
	 */
	public function getProjectUrl(){
	
		return '/project/'.$this->id;
	}

	/**
	 * Return project full url in format <a href="url">Title</a>
	 */
	public function getProjectFullUrl(){
		return '<a href="'.$this->getProjectUrl().'">'.$this->title."</a>";
	}
	
	/**
	 * Date initialization
	 */
	public function __construct($user, $category, $title, $pitchSentence, $content, $priority) {
		// date
		$this->created = new \DateTime ( "now" );
		$this->tags = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->followers = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->modified = new \DateTime ( "now" ); // the date is at the beginning                                      // the same
		$this->level = 1;
		$this->featured = 0;
		$this->viewCount = 0;
		$this->user = $user;
		$this->setCategory ($category);
		$this->title = $title;
		$this->pitchSentence = $pitchSentence;
		$this->content = $content;
		$this->priority = $priority;
		$this->ban = false;
		$this->roles = new \Doctrine\Common\Collections\ArrayCollection ();
		$this->disableRoleWidget = false;
	}
	
	
	/**
	 * Get count of followers of this project
	 */
	public function getCountFollowers(){
		return $this->followers->count();
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function getDisableRoleWidget() {
		return $this->disableRoleWidget;
	}
	
	/**
	 *
	 * @param $value boolean       	
	 */
	public function setDisableRoleWidget($value) {
		$this->disableRoleWidget = $value;
	}
	
	/**
	 * Set path to the picture file
	 * 
	 * @param $path unknown_type       	
	 */
	public function setPicture($picture) {
		$this->picture = $picture;
	}
	
	public function getPicture($size = self::PROJECT_PHOTO_RESOLUTION_LARGE) {
		
		if ($this->picture == null) {
			return null;
		}
			
		$arr = array (self::PROJECT_PHOTO_RESOLUTION_LARGE,
					  self::PROJECT_PHOTO_RESOLUTION_BIG,
					  self::PROJECT_PHOTO_RESOLUTION_SMALL, 
					  self::PROJECT_PHOTO_RESOLUTION_MEDIUM ,
					  self::PROJECT_PHOTO_RESOLUTION_TINY );
		
		if (! in_array ( $size, $arr )) {
			return $this->picture;
		}
	
		$ext = substr ( strrchr ( $this->picture, '.' ), 1 );
		$pre = substr ( $this->picture, 0, strrpos ( $this->picture, '_' ) );
		return $pre . '_' . $size . '.' . $ext;
	}
	
	/**
	 * Return the whole address for image url
	 * @param unknown_type $size
	 */
	public function getPictureUrl($size = self::PROJECT_PHOTO_RESOLUTION_LARGE){
		
		if($this->getPicture($size) == null) {
			$file =  "no_project_image_" . $size . ".png";
			return '/media/slepice-site/1.0.0/img/'.$file;
				
		} else {$file = $this->getPicture($size);
		}
		
		$config = new \Zend_Config(\Zend_Registry::get('config'));
		if($config->app->s3->storage->enabled){			
			return $config->app->s3->storage->web_url.'projects/'.$this->dir."/thumbs/".$file;
		}
		return '/storage/projects/'.$this->dir."/thumbs/".$file;
	
	}
	
	
	/**
	 *
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}
	
	/**
	 *
	 * @return the $issue
	 */
	public function getIssue() {
		return $this->issue;
	}
	
	/**
	 *
	 * @return the $lesson
	 */
	public function getLesson() {
		return $this->lesson;
	}
	
	/**
	 *
	 * @return the $plan
	 */
	public function getPlan() {
		return $this->plan;
	}
	
	/**
	 *
	 * @param $issue field_type       	
	 */
	public function setIssue($issue) {
		$this->issue = $issue;
	}
	
	/**
	 *
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}
	
	/**
	 *
	 * @return the $pitchSentence
	 */
	public function getPitchSentence() {
		return $this->pitchSentence;
	}
	
	/**
	 *
	 * @return the $modified
	 */
	public function getModified() {
		return $this->modified;
	}
	
	/**
	 *
	 * @param $title field_type       	
	 */
	public function setTitle($title) {
		$this->modified = new \DateTime ( 'now' );
		$this->title = $title;
	}
	
	/**
	 *
	 * @param $content field_type       	
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 *
	 * @param $pitchSentence field_type       	
	 */
	public function setPitchSentence($pitchSentence) {
		$this->pitchSentence = $pitchSentence;
	}
	
	/**
	 *
	 * @param $modified \DateTime       	
	 */
	public function setModified() {
		$this->modified = new \DateTime ( 'now' );
	}
	
	/**
	 *
	 * @param $lesson field_type       	
	 */
	public function setLesson($lesson) {
		$this->lesson = $lesson;
	}
	
	/**
	 *
	 * @param $plan field_type       	
	 */
	public function setPlan($plan) {
		$this->plan = $plan;
	}
	
	/**
	 * Set Category
	 */
	public function setCategory($category) {
		$category->addProject ( $this );
		$this->category = $category;
	}
	
	public function setPriority($priority) {
		
		$this->priority = $priority;
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

}