<?php
namespace App\Form;

/**
 * Survey
 * @author misteryyy
 *
 */
class MemberCreateProjectStep4 extends \Twitter_Bootstrap_Form_Horizontal
{
	
	protected $questions; // Array of categories from DB
	
	public function __construct($questions)
	{
		$this->questions = $questions;
		parent::__construct();
	}
	
	
	public function init()
	{
		$this->_addClassNames('fl-form');
		$this->addAttribs(array("id" => "step")); // for jquery stepy plugin
		
		// GENERATE QUESTIONS
		foreach($this->questions as $index => $q){

			$this->addElement('hidden','q_'.$index, array(
				'description' => '<h3 class="fl-bottom10">'.$q.'</h3>',
				'ignore' => true,
				'decorators' => array(array('Description', array('escape'=>false, 'tag'=>'')))
			));


			$this->addElement('textarea', 'question_'.$index, array(
					'label' => '',
					'required' => false,
					'class' => 'span8',
					'rows' => 3,
					'filters' => array('StringTrim'),
					'description' => "max 250 letters",
					'validators' => array(array('StringLength', false, array(1,250)) )
			));
			
			$this->addDisplayGroup(
					array('q_'.$index,'question_'.$index), 'Q'.$index, array('legend' => $q,'title'=>"Q".$index)
			);
		}
		

		// SUBMIT SECTION / works with jquery in view
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Next step",
				'escape' => false,
				'class' => 'btn btn-info'
		));

		$this->addElement('hidden', 'previous', array(
				'description' => '<a class="btn btn-info" href="/member/project/create-project-step-three">Previous</a>',
				'ignore' => true,
				'decorators' => array(
						array('Description', array('escape'=>false, 'tag'=>'')),
				),
		));

		// Action Section
		$this->addDisplayGroup(
				array('previous','submit'),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
}



