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
abstract class BaseEntity extends Object
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
}