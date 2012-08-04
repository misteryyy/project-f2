<?php

namespace App\Form\Member;

class FloBoxAdminForm extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {

    $this->addElement('text', 'title', array(
                'label' => 'Title:',
                'required' => true,
                'class' => 'span8',
                'filters'    => array('StringTrim'),
                //'description' => "Title. Max 50 letters.",
                'validators' => array( array('StringLength', false, array(0,100) ))));
  
    // type of FloMessage
    $options = array( 
    		\App\Entity\UserFloBox::MESSAGE_TYPE_CHOICE => \App\Entity\UserFloBox::MESSAGE_TYPE_CHOICE,
    		\App\Entity\UserFloBox::MESSAGE_TYPE_PROBLEM => \App\Entity\UserFloBox::MESSAGE_TYPE_PROBLEM,
    		\App\Entity\UserFloBox::MESSAGE_TYPE_INTEREST => \App\Entity\UserFloBox::MESSAGE_TYPE_INTEREST,
    		);

    // Country Select Box
    $this->addElement('select','type', array(
    		'label' => 'Type:', 
    		'multiOptions' => $options
    	
    		));
    
    $this->addElement('textarea', 'typeDetail', array(
    		'label' => 'Specicify your idea:',
    		'required' => true,
            'class' => 'span8',
            'rows' => 3,
    		'filters'    => array('StringTrim'),
    		'validators' => array(array('StringLength', false, array(1,100)) )
    ));
     
    
    $this->addElement('textarea', 'message', array(
    			'label' => 'Text:',
    			'required' => true,
    			'filters' => array('StringTrim'),
    			'rows' => 5,
                'class' => 'span8',
    			'description' => "Describe your idea in max 250 letters.",
    			'validators' => array( array('StringLength', false, array(0,250) ))
    	));

 	
 	// submit button
 	$this->addElement('submit','submit',array(
 			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
 			'label' => "Submit",
 			'escape'        => false,
 			'decorators' => array(   
    							array('Description', array('tag' => 'p', 'class' => 'help-block')),
 								array('ViewHelper'),
 								array('HtmlTag', array('tag' => 'div', 'class' => 'controls')),
    							array('Wrapper')
    					),
 	));
 	 
 			/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			array('title','type','typeDetail','message','submit'), 'FLO~Box Idea',	array('legend' => 'FLO~Box Admin')
        	);
       
    
        	     	   
    }
}

