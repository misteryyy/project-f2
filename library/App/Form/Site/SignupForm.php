<?php
namespace App\Form\Site;

class SignupForm extends \Twitter_Bootstrap_Form_Horizontal
{
	public function init()
	{	
	
		$this->_addClassNames('fl-form fl-form-signup');
		$this->addAttribs(array("id" => "form-sign-up"));
		$this->addElement('text', 'name', array(
				'label' => 'Your Name:',
				'class' => 'fl-width97',
				'required' => true,
				'filters' => array('StringTrim'),
				//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
				'description' => "This name will be used in the whole FLO~ system!",
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		));
			
		$this->addElement('text', 'email', array(
				'label' => 'Email:',
				'class' => 'fl-width97',
				'required' => true,
				'errorMessages' => array("You should have email which will simply describe your goal."),
				//'description' => "description",
				'validators' => array("emailAddress"),
		));

		$this->addElement('text', 'email_verification', array(
				'label' => 'Email verification:',
				'required' => true,
				'class' => 'fl-width97',
				'errorMessage' => "Email is not the same as previous one.",
				//'description' => "description",
				'validators' => array(array("emailAddress"),
									  array('Identical', true, array('token' => 'email') )
				)
		));
		
		$this->addElement('password', 'password', array(
				'label' => 'Password:',
				'required' => true,
				'class' => 'fl-width97',
				//'description' => "description",
				'validators' => array( array('StringLength',array(5,20)))
				));
		
		$this->addElement('password', 'password_verification', array(
				'label' => 'Password verification:',
				'required' => true,
				'class' => 'fl-width97',
				'errorMessages' => array("Password is not the same as previous one."),
				//'description' => "description",
				'validators' => array(
						array('Identical', true, array('token' => 'password') ),
						array('StringLength',array(5,20)
						)
				)
		));

		
		 $this->addElement('hidden', 'divider', array(
		 		'description' => '<div class="fl_thin_divider"></div>',
		 		'ignore' => true,
		 		'decorators' => array(
		 				array('Description', array('escape'=>false, 'tag'=>'')),
		 		),
		 ));
		
		
		
		
		
		$this->addElement('radio','verification', array(
				'label' => 'Select green square:',
				'required' => true,
				'errorMessages' => array("You have choose green square. Which is green :)"),
				//'description' => "description",
				'multiOptions' => array("Blue","Green","Red"),
				'validators' => array(
						array('Between',true,array('min' => 1, 'max' => 1),
						)
				),

		));


		
		
// 		$radio->setLabel('Choose green color box:')
// 		->setMultiOptions(array('1' => PHP_EOL . 'Green', '2' => PHP_EOL . 'Blue','3' => PHP_EOL . 'Red','4' => PHP_EOL . 'Black'))
// 		->setRequired(true)
// 		->addValidator('Between',true, array('min' => 1, 'max' => 1)); // value one
		
		

		$this->addElement('checkbox', 'accept', array(
				'label'=>'Do you agree with <a href="/index/rules">rules</a>?',
				'uncheckedValue'=> '',
				'checkedValue' => 'I Agree',
				'validators' => array(
						array('notEmpty', true, array(
								'messages' => array(
										'isEmpty'=>'You must agree to the terms.'
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
				array('name','email','email_verification','password','password_verification','divider','verification','accept'), 'Sign Up', array('legend' => 'Sign up')
		);

		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Sign up for FLO~",
				'class' => 'btn btn-inverse',
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



