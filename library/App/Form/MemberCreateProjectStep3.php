<?php
namespace App\Form;
use App\Entity\UserRole;

class MemberCreateProjectStep3 extends \Twitter_Bootstrap_Form_Horizontal
{
	public function init()
	{		
		$this->_addClassNames('fl-form');
		$this->setAttrib("id", "form-step-3");
		
		$this->addElement('hidden', 'title_roles', array(
			'description' => '<h3 class="fl-bottom10">Your role(s) in this project:</h3>',
			'ignore' => true,
			'decorators' => array(array('Description', array('escape'=>false, 'tag'=>'')),
		),
		));

		$arrayRoles = array(
				array("name" => UserRole::MEMBER_ROLE_STARTER, 
					 "description" => UserRole::MEMBER_ROLE_STARTER
					),
				
				array("name" => UserRole::MEMBER_ROLE_LEADER,
					  "description" => UserRole::MEMBER_ROLE_LEADER
						),
				array("name" => UserRole::MEMBER_ROLE_BUILDER,
					 "description" => UserRole::MEMBER_ROLE_BUILDER
					),
				
				array("name" => UserRole::MEMBER_ROLE_GROWER,
					 "description" => UserRole::MEMBER_ROLE_GROWER
					),
				array("name" => UserRole::MEMBER_ROLE_ADVISER, 
						"description" => UserRole::MEMBER_ROLE_ADVISER 
				)
		 );
		
		$addList = array(); // saving name for element group
	
	foreach($arrayRoles as $role){
			//$addList[] = "role_description_".$role['name'];
			$addList[] = "role_".$role['name'];
			
			// Description of roles
// 			$this->addElement('hidden', 'role_description_'.$role['name'], array(
// 					'description' => '<div class="alert alert-info">'.$role['description'].'</div>',
// 					'ignore' => true,
// 					'decorators' => array(
// 							array('Description', array('escape'=>false, 'tag'=>'')),
// 					),
// 			));
			
			$this->addElement('checkbox','role_'.$role['name'],
					array(
							'label' => $role['name'],
							'id' => "role_".$role['name'],
							'decorators' => array(
							array('ViewHelper'),
							array('ElementErrors'),
							array('Description', array('tag' => 'p', 'class' => 'help-block')),
							array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
							array('Label', array('class' => 'control-label')),
							array('Wrapper'),			
							)
					)
			);
					
		}
		
	
		/**
		 * FIRST FIELD SET
		 */
		$this->addDisplayGroup(
				$addList,
				'creator role',array(	
						'legend' => 'Choose your role',
						'disableLoadDefaultDecorators' => true,
						'decorators' => array(
								array('FormElements'),
								array('Fieldset'),
								)			
				)
		);


		 
$warning_message =  <<<EOT
	<div class="fl-cnt-100">
	  <div class="alert alert-info">
		<span class="label label-info">Info</span>
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
		Description what is role widget for. Description what is role widget for. Description what is role widget for. 
	  </div>
	</div>
EOT;
	
		$this->addElement('hidden', 'warning', array(
				'description' => $warning_message,
				'ignore' => true,
				'decorators' => array(
						array('Description', array('escape'=>false, 'tag'=>'')),
				),
		));

		// Widget Setting
		$this->addElement('checkbox','role_widget_disable',
				array(
						'label' => "Disable Role Widget",
						'description' => 'Please read what will happen if you will disable role widget.',
				)
		);

		$this->addDisplayGroup(
				array('warning','role_widget_disable'),
				'role_widget',
				array('legend' => 'Role Widget Disable')
		);

		$this->addElement('hidden', 'title_survey', array(
			'description' => '<h3 class="fl-bottom10">Questions for project applicants:</h3>',
			'ignore' => true,
			'decorators' => array(array('Description', array('escape'=>false, 'tag'=>'')),
		),
		));



$warning_message =  <<<EOT
	<div class="fl-cnt-100">
	  <div class="alert alert-info">
		<span class="label label-info">Info</span>
		What is survey for.What is survey for.What is survey for.What is survey for.What is survey for.What is survey for.
		What is survey for.What is survey for.What is survey for.What is survey for.What is survey for.What is survey for.
	  </div>
	</div>
EOT;

$this->addElement('hidden', 'warning_survey', array(
		'description' => $warning_message,
		'ignore' => true,
		'decorators' => array(
				array('Description', array('escape'=>false, 'tag'=>'')),
		),
));
				
		// Adding Survey
		$addQuestion [] = 'warning_survey';		
		// generation questions for the project
		for($i = 1;$i <= 5; $i++){
			$addQuestion [] = "question_".$i;
			
			$this->addElement('textarea', 'question_'.$i, array(
					'label' => 'Question #'.$i.': ',
					'required' => false,
					'rows' => 2,
					'class' => 'span8',
					'filters' => array('StringTrim'),
					'validators' => array(array('StringLength', false, array(1,100)) )
			));
		}
			
		$this->addDisplayGroup(
				$addQuestion,
				'role_widget_survey',
				array('legend' => 'Questions for project')
		);


		$this->addElement('button', 'previous', array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => 'Previous step',
				'class' => 'btn btn-info'
		));
		
		$this->addElement('button', 'reset', array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => 'Reset',
				'type' => 'reset',
				'class' => 'btn'
		));
		
		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Next step",
				'escape' => false,
				'class' => 'btn btn-info'
		));
		 	 
		
		 
		// Action Section
		$this->addDisplayGroup(
				array('previous', 'reset', 'submit'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



