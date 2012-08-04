<?php
namespace App\Form;

class MemberCreateProjectStep1 extends \Twitter_Bootstrap_Form_Horizontal
{
	
	protected $categories; // Array of categories from DB
	
	public function __construct($categories)
	{
		$this->categories = $categories;
		parent::__construct();
	}
	
	
	public function init()
	{
		// $this->setIsArray(true);
		// $this->setElementsBelongTo('bootstrap'); // will make form array
		$this->_addClassNames('fl-form');
		
		$this->addElement('text', 'title', array(
				'label' => 'Project name:',
				'required' => true,
				'class' => 'span8',
				'filters' => array('StringTrim'),
				//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
				//'description' => "name of your project",
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		));
	
		
		
		// Priority
		$this->addElement('select','category', array(
				'label' => 'Category:',
				'class' => 'span3',
				'multiOptions' => $this->categories,	 
		));
		
		for($i = 1; $i <=10;$i++){
			$priority[$i] = $i;
		}
		// Passion Bar
		$this->addElement('select','priority', array(
				'label' => 'How serious you are with this project?',
				'description' => "Explanation what the passion is for!",
				'class' => 'span3',
				'multiOptions' => $priority
		));
			
		
		$this->addElement('textarea', 'pitch', array(
				'label' => 'Sentence pitch:',
				'class' => 'span8',
				'row' => '3',
				'required' => true,
				'errorMessages' => array("You should have sentence pitch which will simply describe your goal."),
				'description' => "What is sentence pitch!",
				'filters' => array('StringTrim'),
				'validators' => array("NotEmpty"),
		));

		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Description:',
				'required' => true,
				'errorMessages' => array("You should have descripton of your project."),
				'description' => "description",
				'class' => 'span8',
				'validators' => array("NotEmpty"),
				'disableLoadDefaultDecorators' => true,
		));
		
		$this->addElement('textarea', 'plan', array(
				'label' => 'Plans:',
				'class' => 'span8',
				'rows' => '4',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('textarea', 'issue', array(
				'label' => 'Issues:',
				'class' => 'span8',
				'rows' => '4',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('textarea', 'lesson', array(
				'label' => 'Lessons learned:',
				'class' => 'span8',
				'rows' => '4',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('text', 'project_tags', array(
				'label' => 'Tags:',
				'required' => false,
				'class' => 'span8',
				'filters'    => array (array('StringTrim'), array("StringToLower")),
				'description' => "design, performance, ... ",
				'validators' => array( array('StringLength', false, array(0,250) ),
						array('regex',false, array('/^([\+\-\#\.a-z0-9A-Z ]*[,]?)+$/') ) //
				)
		));

		$this->addDisplayGroup(
				array('title','category','priority','pitch','content','plan','issue','lesson','project_tags'), 'Create Project - General Information', array('legend' => 'Step 1')
		);

		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Next step",
				'escape' => false,
				'class' => 'btn btn-info'
		));
		 
		 
		$this->addElement('button', 'reset', array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => 'Reset',
				'type' => 'reset',
				'class' => 'btn'
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



