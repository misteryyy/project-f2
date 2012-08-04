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
					'multiOptions' => array_merge(array(''=>"All"),$this->categories),
			));
			
			
			for($i = 1; $i <=10;$i++){
				$priority[$i] = $i;
			}
			// Passion Bar
			$this->addElement('select','priority', array(
					'label' => 'Priority',
					'description' => "Passion level?",
					'multiOptions' => array_merge(array(''=>"All"),$priority)
			));
				
			for($i = 1; $i <=10;$i++){
				$priority[$i] = $i;
			}
			// Passion Bar
			$this->addElement('select','level', array(
					'label' => 'Level',
					'description' => "Project Level level?",
					'multiOptions' => array(''=>"All",1 => 1,2 =>2,3 => 3)
			));
			
			$addToGroup[] = 'category';
			$addToGroup[] = 'priority';
				
			// decorator for radios
			$decors = array(
					'ViewHelper',
					array('HtmlTag',array('tag'=>'div','class'=>"fl-feedback-answer")),
					//array('HtmlTag', array('tag' => 'dd')),
					array( array('labelDivClose' => 'HtmlTag'), array('tag' => 'div', 'closeOnly' => true, 'placement' => 'prepend')),
					'Label',
					array(array('labelDivOpen' => 'HtmlTag'), array('tag' => 'div', 'openOnly' => true, 'placement' => 'prepend', 'class' => 'fl-feedback-question')),
					array(array('labelLiOpen' => 'HtmlTag'), array('tag' => 'li', 'openOnly' => true, 'placement' => 'prepend')),
						
			
			);

 			// Project Roles
 			$this->addElement('MultiCheckbox','project_role',array(
 					'label' => "Choose project roles",
 					'multiOptions' => $arrSpecRoles,
 					'disableLoadDefaultDecorators' => true,
 					'decorators' => $decors,
 			));
 			
 			
 			$addToGroup[] = "q";
			$addToGroup[] = "project_role";

			
			$this->addElement('text', 'q', array(
					'label' => 'Keyword',
					'required' => true,
					'filters'    => array('StringTrim'),
					'description' => "Search",
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



