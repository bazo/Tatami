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
class Security extends Object implements \Nette\Security\IAuthenticator
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
	$email = $credentials[self::USERNAME];
	$userEntity = $this->entityManager->getRepository('User')->findOneBy(array('email' => $email));

	//if(!is_object($userEntity) or !($credentials[self::PASSWORD] == $userEntity->password))
	
	if(!is_object($userEntity) or !$this->hasher->checkPassword($credentials[self::PASSWORD], $userEntity->password))
	    throw new AuthenticationException('Email and password mismatch');
	$userData = array(
	    'id' => $userEntity->id,
	    'name' => $userEntity->name,
	    'email' => $userEntity->email
	);
	$userData = \Nette\ArrayHash::from($userData, true);
	return new \Nette\Security\Identity($userEntity->id, $userEntity->getRole()->getName(), $userData);
    }
}