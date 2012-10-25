<?php
namespace App\Entity;

// comment for level change in project
/**
 * @Entity(repositoryClass="App\Repository\ProjectLevelComment")
 * @Table(name="project_level_comment",indexes={@index(name="search_project_level_comment",columns={"project_id","level"})})
 */
class ProjectLevelComment {

	/**
	 * @Id @Column(type="integer", name="id")
	 * @GeneratedValue
	 */
	private $id;
	
	/**
	 * @Column(type="datetime",name="created")
	 */
	private $created;
	
	/**
	 * @Column(type="integer", name="level",nullable=false)
	 */
	private $level;
	
	
	/**
	 * @Column(type="text", name="content",nullable=true)
	 */
	private $content;
	

	/**
	 * @ManyToOne(targetEntity="Project")
	 * @JoinColumn(name="project_id", referencedColumnName="id")
	 */
	private $project;

	/**
	 *
	 * @param $name unknown_type       	
	 */
	public function __construct($project,$content,$level = 1) {
		$this->content = $content;
		$this->project = $project;
		$this->level = $level;
		$this->created = new \DateTime ( "now" );
		
		
	}
	
	

	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $level
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param field_type $level
	 */
	public function setLevel($level) {
		$this->level = $level;
	}

	/**
	 * @param field_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
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