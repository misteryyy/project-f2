<?php
namespace App\Form\Site;
use App\Entity\UserRole;

class BrowseProjectForm extends \Twitter_Bootstrap_Form_Horizontal
{

protected $categories; // Array of categories from DB
	
	public function __construct($categories)
	{
		$this->categories = $categories;
		parent::__construct();
	}
	
	
	public function init()
	{	
		// ajax call
			$this->addAttribs(array("id" => "form-browse-project"));
			$this->setMethod('get');

			$arrSpecRoles = array(UserRole::MEMBER_ROLE_STARTER => UserRole::MEMBER_ROLE_STARTER, 
								 UserRole::MEMBER_ROLE_LEADER => UserRole::MEMBER_ROLE_LEADER, 
								UserRole::MEMBER_ROLE_BUILDER => UserRole::MEMBER_ROLE_BUILDER, 
					UserRole::MEMBER_ROLE_GROWER => UserRole::MEMBER_ROLE_GROWER, 
					UserRole::MEMBER_ROLE_ADVISER => UserRole::MEMBER_ROLE_ADVISER, );
			
			// Priority
			$this->addElement('select','category', array(
					'label' => 'Category',
					'class' => 'fl-width100 fl-margin0',
					'multiOptions' =>  array_merge(array('0'=>"All"),$this->categories),
					'decorators' => array(array('ViewHelper'),array('Label',array('class'=>"hide"))),
			));
			
			
			for($i = 1; $i <=10;$i++){
				$priority[$i] = $i;
			}
			// Passion Bar
			$this->addElement('select','priority', array(
					'label' => 'Passion Level',
					'multiOptions' => array_merge(array('0'=>"All"),$priority),
					'disableLoadDefaultDecorators' => true,
					'decorators' => array(array('ViewHelper'),array('Label')),
			));
				
			// Level
			$this->addElement('select','level', array(
					'label' => 'Select Project Level',
					'class' => "fl-filtr-form-level",
					'multiOptions' => array(''=>"All",1 => 1,2 =>2,3 => 3)
			));
			
			$addToGroup[] = 'category';
			$addToGroup[] = 'priority';
				

 			// Project Roles
 			$this->addElement('MultiCheckbox','project_role',array(
 					'label' => "Choose project roles",
 					'multiOptions' => $arrSpecRoles,
 					'disableLoadDefaultDecorators' => true,
 					'decorators' => array('ViewHelper'),
 			));
 			
 			
 			$addToGroup[] = "q";
			$addToGroup[] = "project_role";

			
			$this->addElement('text', 'q', array(
					'label' => 'Keyword',
					'required' => false,
					'filters'    => array('StringTrim'),
					'class' => "fl-width85",
					'disableLoadDefaultDecorators' => true,
					'decorators' => array('ViewHelper'),
					'validators' => array( array('StringLength', false, array(0,100) ))));
			
			//	submit button
			$this->addElement('submit','submit',array(
					//'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
					'label' => 'Search',
					'escape' => false,
					'class' => "btn btn-primary right",
					'disableLoadDefaultDecorators' => true,
					'decorators' => array("ViewHelper")
			));
				
			$addToGroup[] = 'submit';
			
			/**
			 * ORDERING IN FIELDSET
			 */
			$this->addDisplayGroup(
					$addToGroup,
					'form-browse-projects', array()
			);

	}
}



