<?php

namespace App\Entity\Proxy\__CG__\App\Entity;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class UserSpecificRoleTag extends \App\Entity\UserSpecificRoleTag implements \Doctrine\ORM\Proxy\Proxy
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

    
    public function getName()
    {
        $this->__load();
        return parent::getName();
    }

    public function getSpecificRoles()
    {
        $this->__load();
        return parent::getSpecificRoles();
    }

    public function setName($name)
    {
        $this->__load();
        return parent::setName($name);
    }

    public function addSpecificRole($role)
    {
        $this->__load();
        return parent::addSpecificRole($role);
    }

    public function removeSpecificRole($role)
    {
        $this->__load();
        return parent::removeSpecificRole($role);
    }

    public function getCountOfSpecRolesUsingThisTag()
    {
        $this->__load();
        return parent::getCountOfSpecRolesUsingThisTag();
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
        return array('__isInitialized__', 'id', 'name', 'specRoles');
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