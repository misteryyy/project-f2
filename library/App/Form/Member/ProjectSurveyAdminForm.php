<?php
namespace App\Form\Member;

/**
 * Survey
 * @author misteryyy
 *
 */
class ProjectSurveyAdminForm extends \Twitter_Bootstrap_Form_Horizontal
{
	
	protected $questions; // Array of categories from DB
	protected $answers; // Array of answers from DB
	
	public function __construct($questions,$answers)
	{
		$this->questions = $questions;
		$this->answers = $answers;
		parent::__construct();
	}
	
	
	public function init()
	{	

		$this->_addClassNames('fl-form');
	    $this->addAttribs(array("id" => "step")); // for jquery stepy plugin
		
		// GENERATE QUESTIONS
		foreach($this->questions as $index => $q){	

			$answerObj = $this->answers[$index-1];

			$this->addElement('hidden','a_'.$answerObj->id, array(
				'description' => '<h3 class="fl-bottom10">'.$q.'</h3>',
				'ignore' => true,
				'decorators' => array(array('Description', array('escape'=>false, 'tag'=>'')))
			));

			
			$this->addElement('textarea', 'answer_'.$answerObj->id, array(
					'label' => '',
					'value' => $answerObj->answer, // displaing answers
					'required' => false,
					'class' => 'span8',
					'rows' => 3,
					'filters' => array('StringTrim'),
					'description' => "max 250 letters",
					'validators' => array(array('StringLength', false, array(1,250)) )
			));
			
			$this->addDisplayGroup(
					array('a_'.$answerObj->id, 'answer_'.$answerObj->id), 'Q'.$index, array('legend' => $q,'title'=>"Q".$index)
			);
		}
		

		// SUBMIT SECTION / works with jquery in view
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Save",
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



