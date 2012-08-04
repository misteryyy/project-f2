<?php
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\UserLog")
 * @Table(name="user_log",indexes={@index(name="search_idx_type",columns={"user_id"})})
 */
class UserLog
{
	
	const TYPE_SYSTEM = "system";
	const TYPE_PRIVATE = "private";
	
    /**
     * @Id @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;
    
    
    /** @Column(type="string", name="type") */
    private $type;
    
    
    /** @Column(type="string", name="icon") */
    private $icon;
    
    
    
    
    /** @Column(type="string", name="message") */
    private $message;
    
    /**
     * @column(type="datetime",name="date")
     */
    private $created;
    
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    private $user;
    
    
    /**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param field_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	public function __construct($message,$type = self::TYPE_SYSTEM,$icon="info"){
    	$this->message = $message;
    	$this->type = $type;
    	$this->icon = $icon;
		$this->created = new \DateTime("now");	
    }
    
    public function setUser($user){
    	$this->user = $user;
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