<?php
namespace App\Form\Project;

class AddPollForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	public function init()
	{	
		// ajax call
		$this->addAttribs(array("id" => "form-poll-create"));
		$this->_addClassNames('fl-form fl-form-new-poll');
		
/*
		 $warning_message =  <<<EOT
	<div class="alert alert-info">
		<span class="label label-info">Info</span>
		How this works. How this works. How this works.
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
		 $addGroup[] = 'warning_survey';
*/

		 $this->addElement('text', 'title', array(
		 		'label' => "Poll Name:",
		 		'required' => true,
		 		'filters' => array('StringTrim'),
		 		'class' => 'fl-width99',
		 		//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
		 		//'description' => "name of your new poll",
		 		'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		 ));
		 
		 $addGroup[] = 'title';
		 
		 // Notification about level
		 $this->addElement('hidden', 'divider', array(
		 		'description' => '<div class="fl_thin_divider"></div>',
		 		'ignore' => true,
		 		'decorators' => array(
		 				array('Description', array('escape'=>false, 'tag'=>'')),
		 		),
		 ));
		 $addGroup[] = 'divider'; // make separator
		 	
		 
		 // generation questions for the project
		 for($i = 1;$i <= 5; $i++){
		 	$addGroup[] = "question_".$i;
		 
		 	$this->addElement('text', 'question_'.$i, array(
		 			'label' => 'Question #'.$i.':',
		 			'required' => false,
		 			'placeholder' => 'Question #'.$i,
		 			'filters' => array('StringTrim'),
		 			'class' => 'fl-width99',
		 			'validators' => array(array('StringLength', false, array(1,100)) )
		 	));
		 }
		 

		// Form section
		$this->addDisplayGroup(
				$addGroup,
				'main',
				array( 'legend' => 'Create new poll')
		);
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Create New Poll",
				'class' => "btn btn-info",
				'escape' => false,
		));
		  
		// Action Section
		$this->addDisplayGroup(
				array('submit'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



