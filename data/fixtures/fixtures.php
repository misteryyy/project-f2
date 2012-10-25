<?php
/**
 * Created by Studio Slepice
 * User: Misteryyy
 * Date: 4.4.2012
 * Time: 
 */

// SETTING BASIC SYSTEM ROLES
$role_visitor = new \App\Entity\UserRole();
$role_visitor->setName(\App\Entity\UserRole::SYSTEM_ROLE_VISITOR);
$role_visitor->setType(\App\Entity\UserRole::TYPE_SYSTEM);
$em->persist($role_visitor);

$role_member = new \App\Entity\UserRole();
$role_member->setName(\App\Entity\UserRole::SYSTEM_ROLE_MEMBER);
$role_member->setType(\App\Entity\UserRole::TYPE_SYSTEM);
$em->persist($role_member);

$role_admin = new \App\Entity\UserRole();
$role_admin->setName(\App\Entity\UserRole::SYSTEM_ROLE_ADMIN);
$role_admin->setType(\App\Entity\UserRole::TYPE_SYSTEM);
$em->persist($role_admin);

// CREATING CATEGORIES
$categories = array('Design','Performing arts','Technology', 'Writing', 'Social change', 'Business');
foreach ($categories as $category){
	$cat = new \App\Entity\ProjectCategory($category);
	$em->persist($cat);
}

// CREATING PROJECT SURVEYS QUESTION
 $question = array(
 		'Why do you feel this is the right point  to start a project like this?',
 		'Do you think your project will require you to put all other obligations on hold, if successful?',
 		'Does your career experience put you at an advantage in this project? How so?',
 		'What makes you capable to lead this project?',
 		'In 5 separate words, what are your motivations for starting this project?',
 		
 		'What immediate problem does your idea solve?',
 		'Did the idea come about because it was your own personal problem? Describe the “lightbulb” moment.',
 		'On a scale of 1-10 (10 being the highest), how passionate are you about this idea?',
 		'What is the single-most important thing about your idea/product without which this idea/product will not be what it is?',
 		'What do you need to do to validate your idea?',
 		'Which market type are you? (Bringing a new product into an existing market;bringing a new product into a new market; bringing a new product into an existing market and trying to resegment as a lower-cost entrant/ re-segment that market as a niche entrant; cloning a business model)',
 		'Who are you trying to persuade as your potential customer? Why?',
 		'How do you plan on convincing these people to use your product?',
 		'Where do you find people like this?',
 		'Describe your perfect customer — a person who currently experiences every pain point you solve, who is in a position to spend money to solve it, etc. Sketch out a “character profile” and be as specific as you can in traits, etc.',
 		'How do you plan on reaching out and getting feedback from these customers?',
 		'Have your researched the competition? List the existing alternatives.',
 		'What makes you different and more valuable to your customers than those alternatives?',
 		'Why might your idea not work?',
 		'Have you sought outside feedback and validation? From whom?',
 		'What sort of thoughts have those people expressed about your product/idea?',
 		'Are people willing to pay for your product? If so, do you know how much?',
 		'How and when will you make your first dollar?',
 		'Do you have domain expertise? if not, do you have the skills and time to gain that knowledge?',
 		'How crowded is the market right now?',
 		'What attributes will make this idea/product successful? Why do you believe that these will create success?',
 		'What’s the simplest form of  these attributes that you can build up within 30 days?',
 		'in 60 days?',
 		'What resources are necessary to create each simplified version? How would it differ from your final product vision?',
 		'Who are the influencers in your sector and what is your plan for attracting them?',
 		
 		'What is your motivation for searching for collaborators?',
 		'Have you exhausted your search amongst friends and family?',
 		'Have you brought up the topic of equity and financial rewards with your collaborators?',
 		'What is your timeline for acting on this?',
 		'Have you discussed what sort of decisions will be made alone, and for which you have final say on?',
 		'Have you discussed what sort of decisions will be made as a team?',
 		'And how those will be made?'
);

 

 
 
 
foreach($question as $q){
	$qObj = new \App\Entity\ProjectSurveyQuestion($q);
	$em->persist($qObj);	
}


// Creating Slideshow
$slideshow = new \App\Entity\Slideshow();
$em->persist($slideshow);
$em->flush();



//////////////////////////////////////////////////////////
//Creating Admins

// adding people to account
$project = new \App\Entity\User();
$project->setEmail("j.kortan@gmail.com");
$project->setPassword("pi2131221");
$project->setName("Josef Kortan");

// setting roles
$project->addRole($role_visitor);
$project->addRole($role_member);
$project->addRole($role_admin);

// adding userInfo / skype, phone, ...
$userInfo = new \App\Entity\UserInfo();
$project->setUserInfo($userInfo);
$em->persist($project);



$user2 = new \App\Entity\User();
$user2->setEmail("jake.zahradnik@gmail.com");
$user2->setPassword("Jake123!!!");
$user2->setName("Jake Zahradnik");
// setting roles
$user2->addRole($role_visitor);
$user2->addRole($role_member);
$user2->addRole($role_admin);

// adding userInfo / skype, phone, ...
$userInfo = new \App\Entity\UserInfo();
$user2->setUserInfo($userInfo);

$em->persist($user2);
$em->flush();


$user2 = new \App\Entity\User();
$user2->setEmail("jerry@floplatform.com");
$user2->setPassword("jerry123");
$user2->setName("Gerardo Nunez");
// setting roles
$user2->addRole($role_visitor);
$user2->addRole($role_member);
$user2->addRole($role_admin);

// adding userInfo / skype, phone, ...
$userInfo = new \App\Entity\UserInfo();
$user2->setUserInfo($userInfo);

$em->persist($user2);
$em->flush();


