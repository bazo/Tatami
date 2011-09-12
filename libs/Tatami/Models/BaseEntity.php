<?php
namespace Entity;

use Nette\Object;
use Nette\Environment;
use Nette\Caching\Cache;
/**
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 *
 * @property-read int $id
 */
class BaseEntity extends Object implements \ArrayAccess
{
    protected
        /**
         * @Id
         * @Column(type = "integer")
         * @GeneratedValue
         * @var int
         */
        $id,

         /** @var \Doctrine\ORM\EntityRepository */
        $repository

    ;

    private
        $position = 0,
        $properties = array()
    ;

    public function __construct()
    {
        $this->properties = $this->getProperties();
    }
    
    public function setValues(array $data)
    {
        foreach ($data as $key => $value) {
                $this->__set($key, $value);
        }
    }

    public function getId()
    {
        return $this->id;
    }
    
    public function getProperties()
    {
        $result = array();
        $reflection = new \ReflectionClass(get_called_class());
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED
                | \ReflectionProperty::IS_PRIVATE);
        foreach($properties as $propertyObject)
        {
            $result[] = $propertyObject->name;
        }
        $this->properties = $result;
        unset($result);
        return $this->properties;
    }


    /**
     *
     * @param mixed $offset 
     */
    public function offsetExists ( $offset )
    {
        $this->getProperties();
        return in_array($offset, $this->getProperties());
    }
    /**
     *
     * @param mixed $offset 
     */
    public function offsetGet ( $offset )
    {
        return $this->$offset;
    }
    /**
     *
     * @param mixed $offset
     * @param mixed $value 
     */
    public function offsetSet ( $offset , $value )
    {
        $this->$offset = $value;
    }
    /**
     *
     * @param mixed $offset 
     */
    public function offsetUnset ( $offset )
    {
        $this->$offset = null;
    }
    
    public function current()
    {
        $this->getProperties();
        $key = $this->properties[$this->position];
        return $this->$key;
    }
    
    public function key()
    {
        return $this->position;
    }
    
    public function next()
    {
        ++$this->position;
    }
    
    public function rewind()
    {
        $this->position = 0;
    }
    
    public function valid()
    {
        $this->getProperties();
        return isset($this->properties[$this->position]);
    }
    
    public function __wakeup()
    {
        $this->getProperties();
        $this->position = 0;
    }
   
}