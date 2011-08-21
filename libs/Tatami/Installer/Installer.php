<?php
namespace Tatami;
/**
 * Installer
 *
 * @author Martin Bažík
 */
class Installer 
{
    private
	$loadedClasses,
	$configFile,
	$destinationConfigFile = 'config.neon',
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	$entityManager
    ;
    
    public function __construct($loadedClasses, $configFile, $destinationConfigFile = 'config.neon')
    {
	$this->loadedClasses = $loadedClasses;
	$this->configFile = $configFile;
	$this->destinationConfigFile = $destinationConfigFile;
    }
    
    public function setEntityManager($entityManager) 
    {
	$this->entityManager = $entityManager;
	return $this;
    }
        
    public function getDatabaseDrivers()
    {
	$drivers = array(
	    'pdo_mysql' => 'MySQL',
	    'pdo_pgsql' => 'PostgreSQL',
	    'pdo_sqlite' => 'SQLite',
	    'pdo_oci' => 'Oracle',
	    'pdo_sqlsrv' => 'SQL Server'
	);
	
	return $drivers;
    }
    
    private function getEntities()
    {
	$entities = array();
	foreach($this->loadedClasses as $class => $file)
	{
	    $reflection = new \Nette\Reflection\ClassType($class);
	    $namespace = $reflection->getNamespaceName();
	    if($reflection->getNamespaceName() == 'Entity')
		$entities[] = $class;
	}
	return $entities;
    }
    
    public function installDatabase()
    {
	$schemaTool =  new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $entityClasses = $this->getEntities();
        $classes = array(
            $this->entityManager->getClassMetadata('Entity\User'),
            $this->entityManager->getClassMetadata('Entity\Message'),
            $this->entityManager->getClassMetadata('Entity\Mailbox'),
            $this->entityManager->getClassMetadata('Entity\Module')
        );
	$schemaTool->dropDatabase();
	$schemaTool->createSchema($classes);
	return $this;
    }
    
    public function testDatabaseConnection($connectionParams)
    {
	try
	{
	    $connection = \Doctrine\DBAL\DriverManager::getConnection((array)$connectionParams);
	    $connection->connect();
	    return true;
	}
	catch(\PDOException $e)
	{
	    return false;
	}
    }
    
    public function createAdminUserAccount($login, $password, $email)
    {
	$user = new \Entity\User();

	$user->setLogin($login);
	$user->setPassword($password);
	$user->setEmail($email);
	$user->setRole('admin');

	$this->entityManager->persist($user);
	$this->entityManager->flush();
	    
	return $this;
    }
    
    public function readDatabaseSettings()
    {
	$config = \Nette\Config\NeonAdapter::load($this->configFile);
	if(isset($config['common']['database']))
	    return $config['common']['database'];
	else return array();
    }
    
    public function writeDatabaseSettings($databaseSettings)
    {
	$config = \Nette\Config\NeonAdapter::load($this->configFile);
	$config['common']['database'] = $databaseSettings;
	$config['production']['database'] = $databaseSettings;
	$config['development']['database'] = $databaseSettings;
	$config['console']['database'] = $databaseSettings;
	\Nette\Config\NeonAdapter::save($config, $this->destinationConfigFile);
    }
    
    public function writeInstalled()
    {
	$config = \Nette\Config\NeonAdapter::load($this->destinationConfigFile);
	$config['common'] = array_merge($config['common'], array('installed' => true));
	$config['production'] = array_merge($config['production'], array('installed' => true));
	$config['development'] = array_merge($config['development'], array('installed' => true));
	$config['console'] = array_merge($config['console'], array('installed' => true));
	\Nette\Config\NeonAdapter::save($config, $this->destinationConfigFile);
    }
}