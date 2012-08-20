<?php

namespace App\Form;

class MemberPersonalInfoForm extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {
       
        $this->_addClassNames('fl-form fl-personal-info-form');
       //  $this->setIsArray(true);
       // $this->setElementsBelongTo('bootstrap'); // will make form array
       
    	$this->addElement('text', 'name', array(
    			'label' => 'Name:',
    			'required' => true,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    			//'errorMessages' => array("Your name can't be empty"),
    			//'placeholder' => "Your name or nickname",
    			//'description' => "description",
				'validators' => array( array('alnum',false, array("allowWhiteSpace" => true)), array('StringLength', false, array(1,20)) )			
    	));
    	
    	$this->addElement('text', 'dateOfBirth', array(
    			'label' => 'Date of birth:',
    			'placeholder'   => 'YYYY/MM/DD',
    			'required' => false,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    		    //'errorMessages' => array("The date should be in format"),
    			//'description' => "example (1988/12/31) / YYYY/MM/DD",
    			'validators' => array( array('Date',false,array('format' => 'yyyy/MM/dd'))
    					),   			 
    	));
    	
        $this->addElement('checkbox','dateOfBirthVisibility', array(
				'label' => 'Hide my birthday data in profile',						
				'checkedValue' => '1',
                'decorators' => array(
                        array('FieldSize'),
                        array('ViewHelper'),
                        array('Addon'),
                        array('ElementErrors'),
                        array('Description', array('tag' => 'p', 'class' => 'help-block')),
                        array('Label', array('class' => 'fl-control-label-checkbox','escape' => false)),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'fl-controls-checkbox-tiny-note')),
                        array('Wrapper')
                        )
		));
    
    // Getting countries
    $locale = new \Zend_Locale('en_US');
    $countries = ($locale->getTranslationList('Territory', 'en', 2));
    asort($countries, SORT_LOCALE_STRING);  // sorting countries

    // Country Select Box
    $this->addElement('select','country', array(
    		'label' => 'Country:', 
    		'value' => 'US',
            'class' => 'fl-width97',
    		'multiOptions' => $countries
    	
    		));
    

    	$this->addElement('text', 'phone', array(
    			'label' => 'Phone:',
    			'required' => false,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    			//	'errorMessages' => array("The date should be in format"),
    			//'description' => "Contact phone number in any format you want. Max 50 letters. ",
    			'validators' => array( array('StringLength', false, array(0,50) ))
    	));
    	
    	
    	$this->addElement('text', 'skype', array(
    			'label' => 'Skype:',
    			'required' => false,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    			//	'errorMessages' => array("The date should be in format"),
    			//'description' => "Your skype name. Max 50 letters.",
    			'validators' => array( array('StringLength', false, array(0,50) ))
    			 
    	));
    	
    	$this->addElement('text', 'im', array(
    			'label' => 'IM:',
    			'required' => false,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    			//	'errorMessages' => array("The date should be in format"),
    			//'description' => "Your Instant Messengers accounts. Max 50 letters.",
    			'validators' => array( array('StringLength', false, array(0,50) ))
    	
    	));
    	
    	$this->addElement('text', 'website', array(
    			'label' => 'Website(s):',
    			'required' => false,
                'class' => 'fl-width97',
    			'filters'    => array('StringTrim'),
    			//	'errorMessages' => array("The date should be in format"),
    			//'description' => "Your websites. Max 100 letters.",
    			'validators' => array( array('StringLength', false, array(0,100) ))	 
    	));
    	
    	$this->addElement('textarea', 'description', array(
    			'label' => 'Describe yourself:',
    			'required' => false,
    			'filters'    => array('StringTrim'),
    			'rows' => 5,
                'class' => 'span8',
    			//	'errorMessages' => array("The date should be in format"),
    			//'description' => "Who are you? Describe yourself in max 1000 letters.",
    			'validators' => array( array('StringLength', false, array(0,1000) ))
    	));
    	 
    	$this->addElement('textarea', 'fieldOfInterestTag', array(
    			'label' => 'Field of interests:  ',
    			'required' => false,
                'class' => 'fl-width97',
                'rows' => 2,
    			'filters'    => array (array('StringTrim'), array("StringToLower")),
    			//	'errorMessages' => array("The date should be int format"),
    			//'description' => "outsourcing, start ups, programming, ... ",
    			'validators' => array( array('StringLength', false, array(0,250) ),
    						//	array('alnum',false, array("allowWhiteSpace" => true)), cant use with commas
    							array('regex',false, array('/^([\+\-\#\.a-z0-9A-Z ]*[,]?)+$/') ) // 
    					)
    	));
  	
    	//Member email
    	$this->addElement('text', 'email', array(
    			'label' => 'Email:',
    			'required' => false,
                'class' => 'fl-width97',
    			//'description' => "Your email which is used for login. Can't be changed.",
    			'validators' => array("EmailAddress"),
    			'attribs'    => array('disabled' => 'disabled')
    	));
    	
    	$this->addElement('checkbox','emailVisibility', array(
    				'label' => 'Hide my email in profile',
    				'checkedValue' => '1',
                    'decorators' => array(
                        array('FieldSize'),
                        array('ViewHelper'),
                        array('Addon'),
                        array('ElementErrors'),
                        array('Description', array('tag' => 'p', 'class' => 'help-block')),
                        array('Label', array('class' => 'fl-control-label-checkbox','escape' => false)),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'fl-controls-checkbox-tiny-note')),
                        array('Wrapper')
                        )
    			)
    	);

        $this->addElement('hidden', 'thick', array(
                'description' =>  '<div class="fl_thick_divider"></div>',
                'ignore' => true,
                'decorators' => array(
                        array('Description', array('escape'=>false, 'tag'=>'')),
                ),
        ));

    		
            /**
             * FIELDSET top left
             */
            $this->addDisplayGroup(
                    array('name',
                          'dateOfBirth',
                          'dateOfBirthVisibility',
                          'country',
                          'email',
                          'emailVisibility'
                          ), 
                    'Personal Info', array('legend' => 'Personal Info')
            );

        	/**
        	 * FIELDSET top right
        	 */
         	$this->addDisplayGroup(
        			array('im',
        				  'skype',
        				  'website',
        				  'phone'
        				), 
         			'Contacts',	array('legend' => 'Contacts')
        	);

            /**
             * FIELDSET bottom
             */
            $this->addDisplayGroup(
                    array('thick','description',
                          'fieldOfInterestTag'
                            ), 
                    'About',    array('legend' => 'About')
            );
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Save",
                    'class' => 'btn btn-info',
         			'escape'        => false,
         	));
         	 
         	 
         	$this->addElement('button', 'reset', array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label'         => 'Reset',
         			'type'          => 'reset',
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

