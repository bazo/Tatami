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
	$entityManager,
        
        /**
         * @var \Nette\DI\IContainer 
         */    
        $context,    
            
        $folders = array(
            '%wwwDir%/webtemp',
            '%tempDir%',
            '%tempDir%/cache',
            '%tempDir%/Proxies'
            )
    ;
    
    public function __construct($loadedClasses, $configFile, $context, $destinationConfigFile = 'config.neon')
    {
	$this->loadedClasses = $loadedClasses;
	$this->configFile = $configFile;
        $this->context = $context;
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
    
    public function checkFolders()
    {
        foreach($this->folders as $folder)
        {
            $file = $this->context->expand($folder);
            if(!file_exists($file))
            {
                mkdir($file, 0777, true);
            }
        }
    }
    
    public function installDatabase()
    {
	$schemaTool =  new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
        $entityClasses = $this->getEntities();
        $classes = array();
        foreach($entityClasses as $entityClass)
        {
            $classes[] = $this->entityManager->getClassMetadata($entityClass);
        }
	$schemaTool->dropDatabase();
	$schemaTool->createSchema($classes);
        $this->activateTatami();
        $this->installUserRoles();
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
    
    private function activateTatami()
    {
        $tatami = new \Entity\Module;
        $tatami->setName('Tatami');
        $tatami->setInstalled(true);
        $tatami->setActive(true);
        
        $this->entityManager->persist($tatami);
	$this->entityManager->flush();
    }
    
    private function installUserRoles()
    {
        $user = new \Entity\UserRole;
        $user->setName('User');
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $admin = new \Entity\UserRole;
        $admin->setName('Admin');
        $admin->setParent($user);
        
        $tatamiModule = new Modules\TatamiModule;
        $tatamiPermissions = $tatamiModule->getPermissions();
        
        foreach($tatamiPermissions as $resourceName => $privileges)
        {
            $resource = new \Entity\Resource($resourceName);
            
            foreach($privileges as $privilege => $privilegeText)
            {
                $permission = new \Entity\Permission;
                $permission->setResource($resource)->setPrivilege($privilege)
                        ->setPrivilegeText($privilegeText);
                $admin->addPermission($permission);
            }        
        }
        
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }
    
    public function createAdminUserAccount($login, $password, $email)
    {
	$user = new \Entity\User();

	$user->setLogin($login);
	$user->setPassword($password);
	$user->setEmail($email);
        
        $adminRole = $this->entityManager->getRepository('\Entity\UserRole')
                ->findOneBy(array('name' => 'Admin'));
        
	$user->setRole($adminRole);

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