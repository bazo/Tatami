<?php
namespace Entity;

/** @Entity */
class Resource extends BaseEntity
{
    protected
        /** @Column(type="string", length=50, unique=true) */
        $name
    ;
    
    public function __construct($name = null)
    {
        $this->name = $name;
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


}