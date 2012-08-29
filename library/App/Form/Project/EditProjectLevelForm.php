<?php
namespace App\Form\Project;
use Doctrine\DBAL\Schema\Visitor\RemoveNamespacedAssets;

use App\Entity\UserRole;
/**
 * Form for changing levels for project
 * @author misteryyy
 *
 */
class EditProjectLevelForm extends \Twitter_Bootstrap_Form_Horizontal
{
		
	protected $project; // Array of categories from DB

	public function __construct($project)
	{
		$this->project = $project;
		parent::__construct();
	}
	
	
	public function init()
	{		
			 
// dont move with the EOT, it has to be on the first position
	
		$this->_addClassNames('fl-form');

		
	    $levelArr = array(1 => "LEVEL1",2 => 'LEVEL2', 3 => 'LEVEL3');
	    unset($levelArr[$this->project->level]);
	    
		$this->addElement('select','level', array(
				'label' => 'Choose level',
				//'description' => "description",
				'multiOptions' => $levelArr
		));
		

		$this->addDisplayGroup(
				array('warning','level'),
				'role_widget',
				array('legend' => 'Move on in your project')
		);

	
		// submit button
		$this->addElement('submit','submit',array(
				'buttonType' => \Twitter_Bootstrap_Form_Element_Submit::BUTTON_PRIMARY,
				'label' => "Confirm level",
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



