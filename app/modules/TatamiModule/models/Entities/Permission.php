<?php
namespace Entity;

/** @Entity */
class Permission extends BaseEntity
{
    protected
            
	/**
         * @ManyToOne(targetEntity="Resource", cascade={"persist"})
         */
        $resource,
            
        /** @Column(type="string", length=50, nullable=true) */
        $privilege,
            
        /** @Column(type="string", length=255, nullable=true) */
        $privilegeText
    ;
    
    public function getResource() 
    {
        return $this->resource;
    }

    public function setResource($resource) 
    {
        $this->resource = $resource;
        return $this;
    }

    public function getPrivilege() {
        return $this->privilege;
    }

    public function setPrivilege($privilege) 
    {
        $this->privilege = $privilege;
        return $this;
    }

    public function getPrivilegeText() 
    {
        return $this->privilegeText;
    }

    public function setPrivilegeText($privilegeText) 
    {
        $this->privilegeText = $privilegeText;
        return $this;
    }

    public function __toString()
    {
	return $this->resource->name.' : '.$this->privilege;
    }
    
}