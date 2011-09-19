<?php
namespace Entity;
/** 
 * @Entity 
 */
class Test extends BaseEntity
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
	    
        /** 
	 * @Column(type="string", length=100)
	 */
        $name
    ;
    
    public function setName($name)
    {
	$this->name = $name;
    }
    
    public function getName()
    {
	return $this->name;
    }
}