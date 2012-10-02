<?php
namespace App\Form\Project;

class ProjectBoardForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	public function __construct()
	{		
		parent::__construct();
	}
	
	public function init()
	{	

		$this->_addClassNames('fl-form fl-form-projectboard');
		
		 
		 $this->addElement('text', 'title', array(
		 		'label' => 'Title:',
		 		'required' => true,
		 		'class' => "fl-width99",
		 		'filters'    => array('StringTrim'),
		 		//'description' => "Title. Max 50 letters.",
		 		'validators' => array( array('StringLength', false, array(0,100) ))));
		 	
 
		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Text:',
				'required' => true,
				'rows' => 4,
				'class' => "fl-width99",
				'placeholder' => 'Describe your idea...',
				'errorMessages' => array("You missing content of your comment."),
				//'description' => "description",
				'validators' => array("NotEmpty"),
		));
		
		for($i=0;$i<5;$i++){
		$this->addElement('file', 'file_'.$i, array(
				'label' => 'Attach File #'.($i+1),
				
				'description' => "Max size 4MB (Allowed extenstions: pdf,doc,odt,jpeg,jpg,png)",
				'decorators' => array(
						array('File'),
						array('ElementErrors'),
						array('Description', array('tag' => 'p', 'class' => 'help-block')),
						array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
						array('Label', array('class' => 'control-label')),
						array('Wrapper')
				),
				'filters' => array(
						array('LowerCase'),
						array('ElementErrors'),
						array('Description', array('tag' => 'p', 'class' => 'help-block')),
						array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
						array('Label', array('class' => 'control-label')),
						array('Wrapper')
				)
		));
		
		}
		
		// Form section
		$this->addDisplayGroup(
				array('title','content','file_0','file_1','file_2','file_3','file_4','file_5'),
				'main',
				array( 'legend' => 'add new project message')
		);
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Add on the Project Board",
				'class' => 'btn btn-info',
				'escape' => false,
		));
		
		$this->addElement('button', 'reset', array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => 'Reset',
				'class' => 'btn',
				'type' => 'reset'
		));
		 
		  
		// Action Section
		$this->addDisplayGroup(
				array('reset', 'submit'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



