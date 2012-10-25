<?php
namespace App\Form\Project;

class EditProjectForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	protected $categories; // Array of categories from DB
	protected $project;
	public function __construct($categories,$project)
	{
		$this->categories = $categories;
		$this->project = $project;
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
				'value' => $this->project->title,
				//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
				//'description' => "name of your project", //nepotřebujem description u tohodle
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		));
	
		
		
		// Priority
		$this->addElement('select','category', array(
				'label' => 'Category:',
				'class' => 'span3',
				'value' => $this->project->category->id,
				'multiOptions' => $this->categories,	 
		));
		
		for($i = 1; $i <=10;$i++){
			$priority[$i] = $i;
		}
		// Passion Bar
		$this->addElement('select','priority', array(
				'label' => 'How committed are you to the success of this project?',
				'description' => "If this number is high, you’re signalling to others on the FLO~ Platform that you are very committed to the success of the project. The higher this number, the more likely you are to get people interested in collaborating with you!",
				'class' => 'span3',
				'multiOptions' => $priority,
				'value' => $this->project->priority
		));
			
		
		$this->addElement('textarea', 'pitch', array(
				'label' => 'Sentence Pitch:',
				'class' => 'span8',
				'rows' => '3',
				'value'=> $this->project->pitchSentence,
				'required' => true,
				'errorMessages' => array("You should have sentence pitch which will simply describe your goal."),
				'description' => "In one sentence (if possible) state the problem that you hope your project will solve clearly, concisely, and effectively ie: ‘A new early-stage entrepreneurial development platform utilizing crowdsourced feedback and a clear, step-by-step process’.",
				'filters' => array('StringTrim'),
				'validators' => array("NotEmpty"),
		));

		// TinyMCE configuration in the phtml file
		$this->addElement('textarea', 'content', array(
				'label' => 'Description:',
				'required' => true,
				'errorMessages' => array("You should have descripton of your project."),
				//'description' => "description",
				'filters' => array('StringTrim'),
				'class' => 'span8',
				'value' => $this->project->content,
				'validators' =>	array( array("NotEmpty")),
				'disableLoadDefaultDecorators' => true,
		));
		

		
	
		// Passion Bar
		$this->addElement('select','sub_module', array(
				'label' => 'Choose with subsection you want to edit.',
				'class' => 'span3',
				'multiOptions' => $priority
		));
			
		
		$arr = \App\Entity\ProjectSubContent::$typesArray;
		$addToGroup = array('title','category','priority','pitch','content');
		foreach ($arr as $s){
			$addToGroup[] = $s['name'];

			$o = $this->project->getSubContent($s['type']);
			$val = "";
			$optionsN = array(1=>'Public note',0=>'Private note');
			
			if(isset($o)){
				$val =  $o->content;
				// is it private submodule?
				if($o->visibility){
					$optionsN = array(1=>'Public note',0=>'Private note');
				}else {
					$optionsN = array(0=>'Private note',1=>'Public note');
				}
			} 
					
			// Country Select Box
			$this->addElement('select',$s['name']."_visibility", array(
					//'label' => 'Is this just your private note?',
					'multiOptions' => $optionsN
					 
			));
			
			
			$this->addElement('textarea', $s['name'], array(
					'class' => 'span7',
					'rows' => '6',
					'required' => false,
					'value' => $val,
					'filters' => array('StringTrim'),
					'description' => $s['description'],
			));
			
		}
		
		$this->addElement('text', 'project_tags', array(
				'label' => 'Tags:',
				'required' => false,
				'class' => 'span8',
				'value' => $this->project->getTagsString(),
				'filters'    => array (array('StringTrim'), array("StringToLower")),
				'description' => "design, performance, ... ",
				'filters' => array('StringTrim'),
				'validators' => array( array('StringLength', false, array(0,250) ),
						array('regex',false, array('/^([\+\-\#\.a-z0-9A-Z ]*[,]?)+$/') ) //
				)
		));

		$addToGroup[] = 'project_tags';
		$this->addDisplayGroup(
			$addToGroup, 'editProject', array('legend' => 'Edit General Information')
		);
	
		 
		 
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Save changes",
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
				array('reset','submit'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



