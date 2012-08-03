<?php
namespace App\Form\Project;

class EditProjectForm extends \Twitter_Bootstrap_Form_Horizontal
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
				'label' => 'Project Name',
				'required' => true,
				'filters' => array('StringTrim'),
				//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
				'description' => "name of your project",
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		));
	
		
		
		// Priority
		$this->addElement('select','category', array(
				'label' => 'Category',
				'multiOptions' => $this->categories,	 
		));
		
		for($i = 1; $i <=10;$i++){
			$priority[$i] = $i;
		}
		// Passion Bar
		$this->addElement('select','priority', array(
				'label' => 'Priority',
				'description' => "how serious you are with this project?",
				'multiOptions' => $priority
		));
			
		
		$this->addElement('text', 'pitch', array(
				'label' => 'Sentence Pitch',
				'class' => 'span6',
				'required' => true,
				'errorMessages' => array("You should have sentence pitch which will simply describe your goal."),
				'description' => "description",
				'filters' => array('StringTrim'),
				'validators' => array("NotEmpty"),
		));

		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Description',
				'required' => true,
				'errorMessages' => array("You should have descripton of your project."),
				'description' => "description",
				'filters' => array('StringTrim'),
				'class' => 'span6',
				'validators' =>	array( array("NotEmpty"), array('StringLength', false, array(1,250) )),
				'disableLoadDefaultDecorators' => true,
		));
		
		$this->addElement('textarea', 'plan', array(
				'label' => 'plans',
				'class' => 'span6',
				'rows' => '3',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('textarea', 'issue', array(
				'label' => 'issues',
				'class' => 'span6',
				'rows' => '3',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('textarea', 'lesson', array(
				'label' => 'lessons',
				'class' => 'span6',
				'rows' => '3',
				'required' => false,
				'filters' => array('StringTrim'),
				'description' => "description",
		));
		
		$this->addElement('text', 'project_tags', array(
				'label' => 'Tags',
				'required' => false,
				'filters'    => array (array('StringTrim'), array("StringToLower")),
				//	'errorMessages' => array("The date should be int format"),
				'description' => "design, performance, ... ",
				'filters' => array('StringTrim'),
				'validators' => array( array('StringLength', false, array(0,250) ),
						array('regex',false, array('/^([\+\-\#\.a-z0-9A-Z ]*[,]?)+$/') ) //
				)
		));

		$this->addDisplayGroup(
				array('title','category','priority','pitch','content','plan','issue','lesson','project_tags'), 'editProject', array('legend' => 'Edit General Information')
		);
	
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Save",
				'escape' => false,
		));
		 
		 
		$this->addElement('button', 'reset', array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => 'Reset',
				'type' => 'reset'
		));
		 
		// Action Section
		$this->addDisplayGroup(
				array('submit', 'reset'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



