<?php
namespace Tatami\Security;

use Nette\Object,
    Nette\Security\AuthenticationException,
    Tatami\Security\Passwords\PasswordHasher;

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
	$entityManager,
	    
	/**
	 *
	 * @var Passwords\IPasswordHasher
	 */
	$hasher
    ;
    
    static $instance;
    
    public static function initialize(\Nette\DI\Container $container)
    {
	if(!isset(self::$instance)) self::$instance = new self($container);
	return self::$instance;
    }
    
    private function __construct(\Nette\DI\Container $container)
    {
	$this->entityManager = $container->getService('entityManager');
	$this->hasher = new PasswordHasher();
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
	//$password = self::hashPassword($credentials[self::PASSWORD]);
	
	$userEntity = $this->entityManager->getRepository('User')->findOneBy(array('login' => $login));
	if(!is_object($userEntity) or !$this->hasher->checkPassword($credentials[self::PASSWORD], $userEntity->password))
	    throw new AuthenticationException('Username and password mismatch');
	return new \Nette\Security\Identity($userEntity->id, $userEntity->getRole()->getName(), $userEntity);
    }

    public function isAllowed($role = self::ALL, $resource = self::ALL, $privilege = self::ALL)
    {
        var_dump($role, $resource, $privilege);exit;
	return true;
    }
    
    public function createPasswordChangeToken($email)
    {
	
    }
}