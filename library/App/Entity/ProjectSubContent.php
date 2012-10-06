<?php
/**
 * Project has issues, lessons learned, ... 
 * @author misteryyy
 *
 */
namespace App\Entity;

/**
 * @Entity(repositoryClass="App\Repository\ProjectSubContent")
 * @Table(name="project_sub_content",indexes={@index(name="search_project_sub_content_type",columns={"type"})})
 */
class ProjectSubContent {

	// types of subcontent
	const TYPE_GENERAL = 0;
	const TYPE_LESSON_LEARNED = 1;
	const TYPE_LESSON_LEARNED_TITLE = "Lessons Learned";
	const TYPE_LESSON_LEARNED_DESCRIPTION = "What mistakes have you learned from other  companies/projects/people  that you intend to apply to this one?What successes have you learned from other companies/projects/people that you intend to apply to this one?";
	
	const TYPE_PLAN = 2;
	const TYPE_PLAN_TITLE = "Plans";
	const TYPE_PLAN_DESCRIPTION = " Explain how and what you (your team) will execute on to make this idea/product successful?";
	
	
	const TYPE_ISSUE = 3;
	const TYPE_ISSUE_TITLE = "Issues";
	const TYPE_ISSUE_DESCRIPTION = "What are the things you're currently struggling with?";
	
	
	const TYPE_QUESTION = 4;
	const TYPE_QUESTION_TITLE = "Questions/Help from the Community";
	const TYPE_QUESTION_DESCRIPTION = "Looking for specific expertise and/or advice from the community? Write it here.";
	
	
	const TYPE_RISK = 5;
	const TYPE_RISK_TITLE = "Risks";
	const TYPE_RISK_DESCRIPTION =  "Of all the assumption you've listed, now list the riskiest ones (as in, if you don't confirm and/or solve these right away your project would immediately sink).";
	
	const TYPE_ASSUMPTION = 6;
	const TYPE_ASSUMPTION_TITLE = "Assumptions";
	const TYPE_ASSUMPTION_DESCRIPTION = "List all the unconfirmed (i.e., you haven't tested them out or seen results to confirm) assumptions you're making about your product/project/customer.";
		
	const TYPE_CUSTOMER_PROFILE = 7;
	const TYPE_CUSTOMER_PROFILE_TITLE = "Customer Profile";
	const TYPE_CUSTOMER_PROFILE_DESCRIPTION ="Describe what you've learned about your customer base. Who have you talked to? How have you engaged them? What questions have you asked? What are their characteristics? What are their wants/needs in a product? What are their objections to your project? ";
	
	const TYPE_EXPERIMENT = 8;
	const TYPE_EXPERIMENT_TITLE = "Experiments";
	const TYPE_EXPERIMENT_DESCRIPTION = "List the experiments/test you're currently running to try and confirm your riskiest assumptions. What is your expected outcome?  what insight/result do you feel you need in order to move forward? What's the simplest test you can run to get that result? How do you plan on setting up that test?";
	
	
	const TYPE_EXPERIMENT_RESULT = 9;
	const TYPE_EXPERIMENT_RESULT_TITLE = "Experiments/Results";
	const TYPE_EXPERIMENT_RESULT_DESCRIPTION = "For each of the tests you've run, write down what's been learned.....Why did your assumption pass/fail?  How do you plan on acting on this result?";
	
	const TYPE_MIN_FEAT_SET = 10;
	const TYPE_MIN_FEAT_SET_TITLE = "Minimum Feature Set/MVP";
	const TYPE_MIN_FEAT_SET_DESCRIPTION = "List each of the key features that define your product. For each one you need to begin finding the answer to the question 'Does this 
                                                                                            feature resonate with customers?' Mark each feature accordingly as VERIFIED/TESTING/UNVERIFIED.";
	
	static $typesArray = array(
			array('name'=> 'plan','type' => self::TYPE_PLAN,'title' => self::TYPE_PLAN_TITLE,'description' =>self::TYPE_PLAN_DESCRIPTION),
			array('name'=> 'issue','type' => self::TYPE_ISSUE,'title' => self::TYPE_ISSUE_TITLE,'description' =>self::TYPE_ISSUE_DESCRIPTION),
			array('name'=> 'lesson','type' => self::TYPE_LESSON_LEARNED,'title' => self::TYPE_LESSON_LEARNED_TITLE,'description' =>self::TYPE_LESSON_LEARNED_DESCRIPTION),
			array('name'=> 'question','type' => self::TYPE_QUESTION,'title' => self::TYPE_QUESTION_TITLE,'description' =>self::TYPE_QUESTION_DESCRIPTION),
			array('name'=> 'risk','type' => self::TYPE_RISK,'title' => self::TYPE_RISK_TITLE,'description' =>self::TYPE_RISK_DESCRIPTION),
			array('name'=> 'assumption','type' => self::TYPE_ASSUMPTION,'title' => self::TYPE_ASSUMPTION_TITLE,'description' =>self::TYPE_ASSUMPTION_DESCRIPTION),
			array('name'=> 'customer-profile','type' => self::TYPE_CUSTOMER_PROFILE,'title' => self::TYPE_CUSTOMER_PROFILE_TITLE,'description' =>self::TYPE_CUSTOMER_PROFILE_DESCRIPTION),
			array('name'=> 'experiment','type' => self::TYPE_EXPERIMENT,'title' => self::TYPE_EXPERIMENT_TITLE,'description' =>self::TYPE_EXPERIMENT_DESCRIPTION),
			array('name'=> 'experiment-result','type' => self::TYPE_EXPERIMENT_RESULT,'title' => self::TYPE_EXPERIMENT_RESULT_TITLE,'description' =>self::TYPE_EXPERIMENT_RESULT_DESCRIPTION),
			array('name'=> 'mfv','type' => self::TYPE_MIN_FEAT_SET,'title' => self::TYPE_MIN_FEAT_SET_TITLE,'description' =>self::TYPE_MIN_FEAT_SET_DESCRIPTION),		
			);
	
	/**
	 * @Id @Column(type="integer", name="id")
	 * @GeneratedValue
	 */
	private $id;
		
	/**
	 * @Column(type="string", name="type",nullable=false)
	 */
	private $type;
	
	/**
	 * @Column(type="string", name="title",nullable=false)
	 */
	private $title;
	
	/**
	 * @Column(type="text", name="content",nullable=true)
	 */
	private $content;
	
	/**
	 * @Column(type="datetime",name="created")
	 */
	private $created;

	
	/**
	 * @ManyToOne(targetEntity="Project",inversedBy="subContents")
	 * @JoinColumn(name="project_id", referencedColumnName="id")
	 */
	private $project;
	
	/**
	 * Construstor
	 * @param unknown_type $project
	 * @param unknown_type $title
	 * @param unknown_type $content
	 * @param unknown_type $type
	 */
	public function __construct($title,$content,$type = self::TYPE_GENERAL){
		$this->title = $title;
		$this->content = $content;
		$this->type = $type;		
	 	// set created
	 	$this->created = new \DateTime ( "now" );
	}

	
	
	/**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @param unknown_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param unknown_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @param unknown_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * Set project
	 * @param unknown_type $project
	 */
	public function setProject($project){
		$this->project = $project;
	}
	
	public function __get($property) {
		// If a method exists to get the property call it.
		if (method_exists ( $this, 'get' . ucfirst ( $property ) )) {
			// This will call $this->getPassword() while getting $this->password
			return call_user_func ( array ($this, 'get' . ucfirst ( $property ) ) );
		} else {
			return $this->$property;
			
		}
	}
	
	public function __set($property, $value) {
		// If a method exists to set the property call it.
		if (method_exists ( $this, 'set' . ucfirst ( $property ) )) {
			// This will call $this->setPassword($value) while setting
			// $this->password
			return call_user_func ( array ($this, 'set' . ucfirst ( $property ) ), $value );
		} else {
			$this->$property = $value;
		}
	}
}
    
   