<?php
namespace Entity;
/** 
 * @Entity 
 */
class Widget extends BaseEntity
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
	    
	/**
         * @ManyToOne(targetEntity="Module")
	 * @var Module
         */
	$module,    
	    
        /** 
	 * @Column(type="string", unique=true)
	 * @NotBlank
	 */
        $name,
	    
	/** 
	 * @Column(type="string", unique=true)
	 * @NotBlank
	 */
        $class,
	
	/** @Column(type="boolean") */    
	$active
    ;
    public function getModule()
    {
	return $this->module;
    }

    public function setModule($module)
    {
	$this->module = $module;
	return $this;
    }

    public function getName()
    {
	return $this->name;
    }

    public function setName($name)
    {
	$this->name = $name;
	return $this;
    }

    public function getClass()
    {
	return $this->class;
    }

    public function setClass($class)
    {
	$this->class = $class;
	return $this;
    }

    public function getActive()
    {
	return $this->active;
    }

    public function setActive($active)
    {
	$this->active = $active;
	return $this;
    }


}