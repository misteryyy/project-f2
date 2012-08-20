<?php
namespace App\Form;

/**
 * Survey
 * @author misteryyy
 *
 */
class MemberCreateProjectStep5 extends \Twitter_Bootstrap_Form_Horizontal
{

	public function __construct()
	{
		parent::__construct();
	}

	public function init()
	{
		
		$this->_addClassNames('fl-form fl-step5-form');
		
		$this->addElement('checkbox', 'accept', array(
				'label'=>'Do you agree with <a href="/index/privacy">Terms & Conditions</a>?',
				'uncheckedValue'=> '',
				'checkedValue' => 'I Agree',
				'validators' => array(
						array('notEmpty', true, array(
								'messages' => array(
										'isEmpty'=>'You must agree with Terms & Conditions.'
								)
						))
				),
				'decorators' => array(
						array('FieldSize'),
						array('ViewHelper'),
						array('Addon'),
						array('ElementErrors'),
						array('Description', array('tag' => 'p', 'class' => 'help-block')),
						array('Label', array('class' => 'fl-control-label-checkbox','escape' => false)),
						array('HtmlTag', array('tag' => 'div', 'class' => 'fl-controls-checkbox')),
						array('Wrapper')
						),	
				'required'=>true,
		));
		
		$this->addDisplayGroup(
				array('accept'),'accepted',null);
		
		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Publish",
				'escape' => false,
				'class' => 'btn btn-info strong'
		));

		
		$this->addElement('hidden', 'previous', array(
				'description' => '<a class="btn btn-info" href="/member/project/create-project-step-four">Previous</a>',
				'ignore' => true,
				'decorators' => array(
						array('Description', array('escape'=>false, 'tag'=>'')),
				),
		));
	
		
		
		

		// Action Section
		$this->addDisplayGroup(
				array('previous','submit',),
				'actions',
				array(
						'disableLoadDefaultDecorators' => true,
						'decorators' => array('Actions')
				)
		);




	}
	
	public function isValid($data)
	{
		if (!is_array($data)) {
			require_once 'Zend/Form/Exception.php';
			throw new \Zend_Form_Exception(__METHOD__ . ' expects an array');
		}
	
		
		return parent::isValid($data);
	}
}



