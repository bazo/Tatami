<?php
namespace Tatami\ServiceFactories;
use 
    Nette\DI,
     \Tatami\Services\EntityManager
;
/**
 * EntityManagerFactory
 *
 * @author Martin Bažík
 */
class EntityManagerFactory 
{
    
    public static function create(DI\Container $container)
    {
	$config = new \Doctrine\ORM\Configuration;
        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver($container->params['appDir'].'/models/Entities');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($container->params['tempDir'].'/Proxies');
        $config->setProxyNamespace('Tatami\Proxies');
        $config->setAutoGenerateProxyClasses(true);
        /*
        if ($container->params['productionMode'] == true)
        {
            $config->setAutoGenerateProxyClasses(true);
        }
        else
        {
            $config->setAutoGenerateProxyClasses(false);
        }
        var_dump()
         * 
         */
        try
        {
	    $dbConfig = $container->params['database'];
	    if($dbConfig['prefix'] != '')
	    {
		$evm = new \Doctrine\Common\EventManager;
		// Table Prefix
		$tablePrefix = new \DoctrineExtensions\TablePrefix($dbConfig['prefix']);
		$evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
		$em = EntityManager::create($dbConfig, $config, $evm);
	    }
	    else
		$em = EntityManager::create($dbConfig, $config);
	    return $em;
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
	catch(\Doctrine\DBAL\DBALException $e)
        {
            echo $e->getMessage();
        }
    }
}