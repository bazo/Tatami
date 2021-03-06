<?php
namespace Tatami\Services;
use 
    Doctrine\ORM\EntityManager as DoctrineEM,
    Doctrine\ORM\ORMException,
    Doctrine\Common\EventManager,
    Doctrine\ORM\Configuration,
    Tatami\Models\Repositories\EntityRepository,
    Symfony\Component\Validator\ValidatorInterface
;
/**
 * Description of EntityManager
 *
 * @author Martin
 */
class EntityManager extends DoctrineEM
{
    private
	/** @var ValidatorInterface */
	$validator
    ;
    
    /**
     * Factory method to create EntityManager instances.
     *
     * @param mixed $conn An array with the connection parameters or an existing
     *      Connection instance.
     * @param Configuration $config The Configuration instance to use.
     * @param EventManager $eventManager The EventManager instance to use.
     * @return EntityManager The created EntityManager.
     */
    public static function create($conn, Configuration $config, EventManager $eventManager = null)
    {
        if (!$config->getMetadataDriverImpl()) {
            throw ORMException::missingMappingDriverImpl();
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, ($eventManager ?: new EventManager()));
        } else if ($conn instanceof Connection) {
            if ($eventManager !== null && $conn->getEventManager() !== $eventManager) {
                 throw ORMException::mismatchedEventManager();
            }
        } else {
            throw new \InvalidArgumentException("Invalid argument: " . $conn);
        }

        return new self($conn, $config, $conn->getEventManager());
    }
    
    public function setValidator(ValidatorInterface $validator)
    {
	$this->validator = $validator;
    }
    
    /**
     * Gets the repository for an entity class.
     *
     * @param string $entityName The name of the entity.
     * @return Tatami\Models\Repositories\EntityRepository The repository class.
     */
    public function getRepository($entityName)
    {
	$entityName = 'Entity\\'.$entityName;
	$entityName = ltrim($entityName, '\\');
        if (isset($this->repositories[$entityName])) {
            return $this->repositories[$entityName];
        }

        $metadata = $this->getClassMetadata($entityName);
        $customRepositoryClassName = $metadata->customRepositoryClassName;

        if ($customRepositoryClassName !== null) {
            $repository = new $customRepositoryClassName($this, $metadata);
        } else {
            $repository = new EntityRepository($this, $metadata);
        }

        $this->repositories[$entityName] = $repository;

        return $repository;
    }
    
    /**
     * Tells the EntityManager to make an instance managed and persistent.
     *
     * The entity will be entered into the database at or before transaction
     * commit or as a result of the flush operation.
     * 
     * NOTE: The persist operation always considers entities that are not yet known to
     * this EntityManager as NEW. Do not pass detached entities to the persist operation.
     *
     * @param object $object The instance to make managed and persistent.
     */
    public function persist($entity)
    {
        $validationResult = $this->validator->validate($entity);
	//var_dump($validationResult);exit;
	parent::persist($entity);
    }
}