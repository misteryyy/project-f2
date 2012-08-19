<?php
namespace App\Form\Project;

class AddCommentFromCreatorForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	private $member = null;

	
	public function __construct($member,$project_id)
	{
		$this->member = $member;
		parent::__construct();
	}

	public function init()
	{	

		$this->_addClassNames('fl-form fl-form-comment');
		 
		 // Description of roles
		 $this->addElement('hidden', 'comment_id', array());

		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Logged as: '.$this->member['name'],
				'required' => true,
				'rows' => 4,
				'class' => "fl-width99",
				'placeholder' => 'Write your respond...',
				'errorMessages' => array("You missing content of your comment."),
				//'description' => "description",
				'validators' => array("NotEmpty"),
		));

		
		// Form section
		$this->addDisplayGroup(
				array('content','comment_id'),
				'main',
				array( 'legend' => 'Answer')
		);
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Respond",
				'class' => 'btn btn-info',
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



