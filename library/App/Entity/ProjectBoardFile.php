<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\Project\ProjectBoardFile")
 * @Table(name="project_board_file",indexes={@index(name="search_project_board_file", columns={"project_board_comment_id"})})
 */
class ProjectBoardFile {
	
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
	 * @ManyToOne(targetEntity="ProjectBoardComment", inversedBy="files", cascade={"persist","delete"})
	 * @JoinColumn(name="project_board_comment_id", referencedColumnName="id")
	 */
	private $projectBoard;

	/**
	 * @Column(type="string", name="name")
	 */
	private $name;
	
	/**
	 * @Column(type="string", name="file")
	 */
	private $file;
	
	/**
	 * @Column(type="string", name="type")
	 */
	private $type;
	
	/**
	 * @Column(type="integer", name="size")
	 */
	private $size;
	
	

	/**
	 * 
	 * @param unknown_type $name
	 * @param unknown_type $type
	 * @param unknown_type $size
	 * @param real name $file
	 */
	public function __construct($name,$type,$size,$file) {
		$this->name = $name;
		$this->type = $type;
		$this->size = $size;
		$this->file = $file;
		$this->created = new \DateTime ( "now" );
		
	}
	public function getSizeFormat($precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		
		$bytes = max($this->size, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
	
		$bytes /= pow(1024, $pow);
	
		return round($bytes, $precision) . ' ' . $units[$pow];
	}
	
	public function addProjectBoardComment($c){
		$this->projectBoard = $c;
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