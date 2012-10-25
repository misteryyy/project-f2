<?php

namespace App\Entity\Proxy\__CG__\App\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class ProjectSubContent extends \App\Entity\ProjectSubContent implements \Doctrine\ORM\Proxy\Proxy
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

    
    public function getVisibility()
    {
        $this->__load();
        return parent::getVisibility();
    }

    public function setVisibility($visibility)
    {
        $this->__load();
        return parent::setVisibility($visibility);
    }

    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int) $this->_identifier["id"];
        }
        $this->__load();
        return parent::getId();
    }

    public function getType()
    {
        $this->__load();
        return parent::getType();
    }

    public function getTitle()
    {
        $this->__load();
        return parent::getTitle();
    }

    public function getContent()
    {
        $this->__load();
        return parent::getContent();
    }

    public function setId($id)
    {
        $this->__load();
        return parent::setId($id);
    }

    public function setType($type)
    {
        $this->__load();
        return parent::setType($type);
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

    public function setProject($project)
    {
        $this->__load();
        return parent::setProject($project);
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
        return array('__isInitialized__', 'id', 'type', 'title', 'content', 'created', 'visibility', 'project');
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