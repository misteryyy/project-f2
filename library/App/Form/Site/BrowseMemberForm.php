<?php
namespace App\Form\Site;
use App\Entity\UserRole;

class BrowseMemberForm extends \Twitter_Bootstrap_Form_Horizontal
{

	public function __construct()
	{
		parent::__construct();
	
	}
	
	public function init()
	{	
		// ajax call
			$this->addAttribs(array("id" => "form-browse-members"));
			$this->setMethod('get');

			$arrSpecRoles = array(UserRole::MEMBER_ROLE_STARTER => UserRole::MEMBER_ROLE_STARTER, 
								 UserRole::MEMBER_ROLE_LEADER => UserRole::MEMBER_ROLE_LEADER, 
								UserRole::MEMBER_ROLE_BUILDER => UserRole::MEMBER_ROLE_BUILDER, 
					UserRole::MEMBER_ROLE_GROWER => UserRole::MEMBER_ROLE_GROWER, 
					UserRole::MEMBER_ROLE_ADVISER => UserRole::MEMBER_ROLE_ADVISER, );
			

			// decorator for radios
			$decors = array(
					'ViewHelper',
			);
			
			// Self-assigned roles
 			$this->addElement('MultiCheckbox','specific_role',array(
 					'multiOptions' => $arrSpecRoles,	
 					'disableLoadDefaultDecorators' => true,
 					'decorators' => $decors,
			));
 			
 			// Project Roles
 			$this->addElement('MultiCheckbox','project_role',array(
 					'label' => "Choose project roles",
 					'multiOptions' => $arrSpecRoles,
 					'disableLoadDefaultDecorators' => true,
 					'decorators' => $decors,
 			));
 			
 			
 			$addToGroup[] = "q";
			$addToGroup[] = "specific_role";
			$addToGroup[] = "project_role";
			
			$this->addElement('text', 'q', array(
					'label' => 'Keyword',
					'class' => 'fl-width90 text-left',
					'required' => true,
					'filters'    => array('StringTrim'),
					'description' => "Search",
					'validators' => array( array('StringLength', false, array(0,100) )),
					'disableLoadDefaultDecorators' => true,
					'decorators' => array("ViewHelper")
					
					)
					
					);
			
			//	submit button
			$this->addElement('submit','submit',array(
					//'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
					'label' => 'Search',
					'escape' => false,
					'class' => "btn btn-info  fl-width30",
					'disableLoadDefaultDecorators' => true,
					'decorators' => array("ViewHelper")
			));
				
			$addToGroup[] = 'submit';
			
			/**
			 * ORDERING IN FIELDSET
			 */
			$this->addDisplayGroup(
					$addToGroup,
					'update-edit', array()
			);
			
		
		


	}
}



