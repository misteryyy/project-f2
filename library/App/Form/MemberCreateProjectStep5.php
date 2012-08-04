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
						array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
						array('Label', array('class' => 'control-label','escape' => false)),
						array('Wrapper')
				),
				'required'=>true,
		));
		
		$this->addDisplayGroup(
				array('accept'),'accepted',null);
		
		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Publish Project",
				'escape' => false,
		));
		// Action Section
		$this->addDisplayGroup(
				array('submit',),
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



