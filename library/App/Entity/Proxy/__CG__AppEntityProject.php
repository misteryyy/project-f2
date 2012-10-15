<?php

namespace App\Entity\Proxy\__CG__\App\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class Project extends \App\Entity\Project implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    /** @private */
    public function __load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;

            if (method_exists($this, "__wakeup")) {
                // call this after __isInitialized__to avoid infinite recursion
                // but before loading to emulate what ClassMetadata::newInstance()
                // provides.
                $this->__wakeup();
            }

            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    /** @private */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    
    public function update()
    {
        $this->__load();
        return parent::update();
    }

    public function getCreated()
    {
        $this->__load();
        return parent::getCreated();
    }

    public function getCreatorRolesArray()
    {
        $this->__load();
        return parent::getCreatorRolesArray();
    }

    public function setDir($dir)
    {
        $this->__load();
        return parent::setDir($dir);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getUser()
    {
        $this->__load();
        return parent::getUser();
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function isFollower($user)
    {
        $this->__load();
        return parent::isFollower($user);
    }

    public function setLevel($level)
    {
        $this->__load();
        return parent::setLevel($level);
    }

    public function getViewCount()
    {
        $this->__load();
        return parent::getViewCount();
    }

    public function setViewCount($viewCount)
    {
        $this->__load();
        return parent::setViewCount($viewCount);
    }

    public function addTag($tag)
    {
        $this->__load();
        return parent::addTag($tag);
    }

    public function addProjectRole($role)
    {
        $this->__load();
        return parent::addProjectRole($role);
    }

    public function addSubContent($content)
    {
        $this->__load();
        return parent::addSubContent($content);
    }

    public function getSubContents()
    {
        $this->__load();
        return parent::getSubContents();
    }

    public function getSubContent($type)
    {
        $this->__load();
        return parent::getSubContent($type);
    }

    public function addProjectUpdate($update)
    {
        $this->__load();
        return parent::addProjectUpdate($update);
    }

    public function addRoleWidgetQuestion($q)
    {
        $this->__load();
        return parent::addRoleWidgetQuestion($q);
    }

    public function getTag($name)
    {
        $this->__load();
        return parent::getTag($name);
    }

    public function removeTag($tag)
    {
        $this->__load();
        return parent::removeTag($tag);
    }

    public function getTagsString($toString = true)
    {
        $this->__load();
        return parent::getTagsString($toString);
    }

    public function getTagsArray()
    {
        $this->__load();
        return parent::getTagsArray();
    }

    public function getTags()
    {
        $this->__load();
        return parent::getTags();
    }

    public function getProjectUrl()
    {
        $this->__load();
        return parent::getProjectUrl();
    }

    public function getProjectFullUrl()
    {
        $this->__load();
        return parent::getProjectFullUrl();
    }

    public function getCountFollowers()
    {
        $this->__load();
        return parent::getCountFollowers();
    }

    public function getDisableRoleWidget()
    {
        $this->__load();
        return parent::getDisableRoleWidget();
    }

    public function setDisableRoleWidget($value)
    {
        $this->__load();
        return parent::setDisableRoleWidget($value);
    }

    public function setPicture($picture)
    {
        $this->__load();
        return parent::setPicture($picture);
    }

    public function getPicture($size = 'large')
    {
        $this->__load();
        return parent::getPicture($size);
    }

    public function getPictureUrl($size = 'large')
    {
        $this->__load();
        return parent::getPictureUrl($size);
    }

    public function getCategory()
    {
        $this->__load();
        return parent::getCategory();
    }

    public function getContent()
    {
        $this->__load();
        return parent::getContent();
    }

    public function getPitchSentence()
    {
        $this->__load();
        return parent::getPitchSentence();
    }

    public function getModified()
    {
        $this->__load();
        return parent::getModified();
    }

    public function setTitle($title)
    {
        $this->__load();
        return parent::setTitle($title);
    }

    public function setContent($content)
    {
        $this->__load();
        return parent::setContent($content);
    }

    public function setPitchSentence($pitchSentence)
    {
        $this->__load();
        return parent::setPitchSentence($pitchSentence);
    }

    public function setModified()
    {
        $this->__load();
        return parent::setModified();
    }

    public function setCategory($category)
    {
        $this->__load();
        return parent::setCategory($category);
    }

    public function setPriority($priority)
    {
        $this->__load();
        return parent::setPriority($priority);
    }

    public function __get($property)
    {
        $this->__load();
        return parent::__get($property);
    }

    public function __set($property, $value)
    {
        $this->__load();
        return parent::__set($property, $value);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'title', 'content', 'pitchSentence', 'disableRoleWidget', 'dir', 'created', 'viewCount', 'picture', 'ban', 'featured', 'priority', 'level', 'modified', 'roles', 'roleWidgetQuestions', 'followers', 'category', 'subContents', 'user', 'tags');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}