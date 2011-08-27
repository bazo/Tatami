<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */
namespace Core;

use Nette\Object;
use Nette\Security\AuthenticationException;


/**
 * Users authenticator.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class Security extends Object implements \Nette\Security\IAuthenticator, \Nette\Security\IAuthorizator
{

    private 
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	$entityManager
    ;
    
    static $instance;
    
    public static function initialize(\Nette\DI\Container $container)
    {
	
	\Nette\Diagnostics\Debugger::fireLog(__METHOD__);
	\Nette\Diagnostics\Debugger::fireLog(self::$instance);
	if(!isset(self::$instance)) self::$instance = new self($container);
	return self::$instance;
    }
    
    private function __construct(\Nette\DI\Container $container)
    {
	$this->entityManager = $container->getService('EntityManager');
    }
    
    /**
     * Performs an authentication
     * @param  array
     * @return IIdentity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
	$login = $credentials[self::USERNAME];
	$password = \sha1($credentials[self::PASSWORD]);
	$userEntity = $this->entityManager->getRepository('Entity\User')->findOneBy(array('login' => $login));
	if(!is_object($userEntity) or $userEntity->password != $password)
	    throw new AuthenticationException('Username and password mismatch');
	return new \Nette\Security\Identity($userEntity->id, 'admin', $userEntity);
    }

    public function isAllowed($role = self::ALL, $resource = self::ALL, $privilege = self::ALL)
    {
	return true;
    }
}