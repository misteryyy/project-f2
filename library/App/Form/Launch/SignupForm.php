<?php
namespace App\Form\Launch;

class SignupForm extends \Twitter_Bootstrap_Form_Horizontal
{
	public function init()
	{	
	
		$this->addAttribs(array("id" => "form-sign-up-beta"));

		$this->addElement('hidden', 'form_description', array(
		 		'description' => '<div class="lp-cnt-90"><p class="lp-donate">Interested in being a part of our beta-testing phase? Take a look at our <a href="/index/faq" title="Frequently asked questions">FAQ</a>s section, and then sign up below. This will be a critical time for us, and your input is going to make or break how successful the application will be in the early stages. Upon signing up, you’ll receive an e-mail explaining the upcoming process in further detail, along with information on when you can start using your account. Sign up, and join the team!</p></div>',
		 		'ignore' => true,
		 		'decorators' => array(
		 			array('Description', array('escape'=>false, 'tag'=>'')),
		 		),
		));

		$this->addElement('text', 'name', array(
				'label' => 'Name',
				'required' => true,
				'filters' => array('StringTrim'),
				//'errorMessages' => array("You have to have project title. You can use just letters and numbers"),
				//'description' => "name description",
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,100)) )
		));
			
		$this->addElement('text', 'email', array(
				'label' => 'Email',
				'required' => true,
				'errorMessages' => array("You should have email which will simply describe your goal."),
				//'description' => "description",
				'validators' => array("emailAddress"),
		));

		$this->addElement('text', 'email_verification', array(
				'label' => 'Email verification',
				'required' => true,
				'errorMessage' => "Hmm, this doesn't seem to match the previous field. Try again.",
				//'description' => "description",
				'validators' => array(array("emailAddress"),
									  array('Identical', true, array('token' => 'email') )
				)
		));
		
		$this->addElement('password', 'password', array(
				'label' => 'Password',
				'required' => true,
				//'description' => "description",
				'validators' => array( array('StringLength',array(5,20)))
				));
		
		$this->addElement('password', 'password_verification', array(
				'label' => 'Password verification',
				'required' => true,
				'errorMessages' => array("Hmm, this doesn't seem to match the previous field. Try again."),
				//'description' => "description",
				'validators' => array(
						array('Identical', true, array('token' => 'password') ),
						array('StringLength',array(5,20)
						)
				)
		));
		
		
		// Passion Bar
		$this->addElement('select','location', array(
				'label' => 'Do you live in Prague?',
				//'description' => "description",
				'multiOptions' => array("no",'yes')
		));
			
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

				array('form_description','name','email','email_verification','password','password_verification','location','accept'), 'Sign Up', array('legend' => 'Early Access')
		);

		
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Sign Up",
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



