<?php
namespace App\Form;
use App\Entity\UserRole;

class MemberCreateProjectStep3 extends \Twitter_Bootstrap_Form_Horizontal
{
	public function init()
	{		
		$this->_addClassNames('fl-form');
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
			
			$this->addElement('checkbox','role_'.$role['name'],
					array(
							'label' => $role['name'],
							'id' => "role_".$role['name'],
							'decorators' => array(
							array('ViewHelper'),
							
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
						'legend' => 'Choose your role:',
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
	
	

	

		
		
// Disable buttong
$this->addElement('checkbox','role_widget_disable',
		array(
				'label' => "Disable Role Widget",
				'disableLoadDefaultDecorators' => true,
				'decorators' => array(
						array('ViewHelper'),
						array('Label')
						
				)
		)
);

// Question Section

$this->addElement('hidden', 'title_survey', array(
		'description' => '<h3 class="fl-bottom10">Questions for project applicants:</h3>',
		'ignore' => true,
		'decorators' => array(array('Description', array('escape'=>false, 'tag'=>'')),
		),
));
$addQuestion [] = "title_survey";

$warning_message =  <<<EOT
	<div class="fl-cnt-100">
	  <div class="alert alert-info">
		As users who match the skills you need become interested in your idea, they can get in touch with you by requesting to join  your project as a collaborator. Think about the types of questions you would  want to ask an applicant to gauge how well you match each other. Try to make your questions relevant and in-depth.
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
				array('legend' => '')
		);

			$this->addElement('hidden', 'previous', array(
				'description' => '<a class="btn btn-info" href="/member/project/create-project-step-two">Previous</a>',
				'ignore' => true,
				'decorators' => array(
						array('Description', array('escape'=>false, 'tag'=>'')),
				),
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



