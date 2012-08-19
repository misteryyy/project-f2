<?php
namespace App\Form\Project;

class AddCommentForm extends \Twitter_Bootstrap_Form_Horizontal
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
		

		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Logged as: '.$this->member['name'],
				'required' => true,
				'rows' => 4,
				'class' => "fl-width99",
				'placeholder' => 'Write your comment...',
				'errorMessages' => array("You missing content of your comment."),
				//'description' => "description",
				'validators' => array("NotEmpty"),
		));
		
		// Widget Setting
		$this->addElement('checkbox','priority',
				array(  'required' => false,
						'label' => "I want answer from project Creator",
						'disableLoadDefaultDecorators' => true,
						'decorators' => array(
						  array('ViewHelper'),
						  array('Label')
						)
				)
		);
		
		// Form section
		$this->addDisplayGroup(
				array('content','priority'),
				'main',
				array( 'legend' => 'add new comment')
		);
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Comment",
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



