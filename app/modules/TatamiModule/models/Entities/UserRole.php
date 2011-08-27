<?php
namespace Entity;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/** @Entity */
class UserRole extends BaseEntity
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
            
        /** @Column(type="string", length=50, nullable=false) */
        $name,
            
        /**
         * @OneToOne(targetEntity="UserRole")
         * @Column(nullable=true)
         */
        $parent = null
    ;
    
    public function getId() 
    {
        return $this->id;
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

    public function getParent() 
    {
        return $this->parent;
    }

    public function setParent(UserRole $parent) 
    {
        $this->parent = $parent;
        return $this;
    }


}