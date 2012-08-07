<?php

namespace App\Form;

use App\Entity\UserRole;

class MemberSkill extends \Twitter_Bootstrap_Form_Horizontal
{
   public function init()
    {

        
    	
    $arrayRoles = array(array("name" => UserRole::MEMBER_ROLE_STARTER, ),
    					array("name" => UserRole::MEMBER_ROLE_LEADER),
    					array("name" => UserRole::MEMBER_ROLE_BUILDER),
    					array("name" => UserRole::MEMBER_ROLE_GROWER),
    					array("name" => UserRole::MEMBER_ROLE_ADVISER)
    		);	
    
    $addList = array(); // saving name for element group

       
    foreach($arrayRoles as $role){
    
    	$addList[] = "role_".$role['name'];
    	$addList[] = "role_".$role['name'].'_tags';
    	 
    	$this->addElement('checkbox','role_'.$role['name'], array(
    			'label' => $role['name'],
                'id' => "role_".$role['name'],
                'decorators' => array( array('ViewHelper'),)
    	));
    	
    	$this->addElement('text', 'role_'.$role['name'].'_tags', array(
    			'required' => false,
    			'filters'    => array (array('StringTrim'), array("StringToLower")),
    			//	'errorMessages' => array("The date should be int format"),
                'placeholder' => 'be more specific...',
    			//'description' => "outsourcing, start ups, programming, ... ",
    			'validators' => array( array('StringLength', false, array(0,250) ),
    					array('regex',false, array('/^([\+\-\#\.a-z0-9A-Z ]*[,]?)+$/') ) //
    			)
    	));	 	
    }
    		
        	/**
        	 * ORDERING IN FIELDSET
        	 */
         	$this->addDisplayGroup(
        			$addList, 
         			'memberskills',
         			array('legend' => 'Member skills')
        	);
       
         	// submit button
         	$this->addElement('submit','submit',array(
         			'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
         			'label' => "Save",
         			'escape'        => false,
                    'class' => 'btn btn-info'
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

