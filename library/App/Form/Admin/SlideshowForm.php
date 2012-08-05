<?php
namespace App\Form\Admin;

class SlideshowForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	private $project= null;
	private $slot = null;
	
	public function __construct($project,$slot)
	{
		$this->project = $project; 
		$this->slot = $slot;
		parent::__construct();
	}
	
	/**
	 * Get current project
	 */
	public function getProject(){
		return $this->project;
	}
	
	public function init()
	{		
		if($this->project){
			$value = $this->project->id;
		} else {
			$message =  '<div class="alert alert-info">SLOT '.$this->slot.'<strong> is empty </strong></div>';
			$value = null;
		}
		
		
		 
		 $this->addElement('hidden', 'slot_position', array('value'=> $this->slot));
		 
		 $this->addElement('text', 'project_id', array(
		 		'label' => 'Project ID',
		 		'value' => $value,
		 		'dimension' => 1,
		 		'class' => 'fl-width95',
		 		'required' => true,
		 		'filters' => array('StringTrim'),
		 		'validators' => array( array('Int',false, array(""))) 
		 ));
 	
		 
		 // submit button
		 $this->addElement('submit','submit',array(
		 		'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
		 		'label' => "Update Slot ".$this->slot,
		 		'class' => "btn fl-width100",
		 		'escape' => false,
		 		'decorators' => array( array("ViewHelper"))
		 ));
		 
		 
	
	
	




	}
}



