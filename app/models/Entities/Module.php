<?php

namespace Entity;

/** @Entity */
class Module extends BaseEntity
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
        /** @Column(type="string", length=50) */
        $name,
        /** @Column(type="boolean") */
        $active,
            
        /** @Column(type="boolean") */
        $installed
    ;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
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

    public function getInstalled() 
    {
        return $this->installed;
    }

    public function setInstalled($installed) 
    {
        $this->installed = $installed;
        return $this;
    }


}