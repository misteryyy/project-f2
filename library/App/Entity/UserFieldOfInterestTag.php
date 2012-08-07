<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\UserFieldOfInterestTag")
 * @Table(name="user_field_of_interest_tag",indexes={@index(name="search_idx",columns={"name"})})
 */
class UserFieldOfInterestTag
{
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;
    
    /** @Column(type="string", name="name",unique=true) */
    private $name;
    
    /**
     * 
     * @ManyToMany(targetEntity="User",mappedBy="userFieldOfInterestTags", cascade={"persist"})
     */
    private $users;
    
    
    public function __construct(){
    	$this->users = new \Doctrine\Common\Collections\ArrayCollection();	
    }
    
    /**
	 * @return the $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return the $users
	 */
	public function getUsers() {
		
		return $this->users;
	}
	
	/**
	 * @param field_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @param field_type $users
	 */
	public function addUser($user) {
		$this->users[] = $user;
	}

	public function removeUser($user){
		$this->users->removeElement($user);
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