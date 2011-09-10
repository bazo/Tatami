<?php
namespace Entity;
use Tatami\Security\Security, Tatami\Security\Passwords\PasswordHasher;
/** 
 * @Entity 
 * @HasLifecycleCallbacks
 */
class User extends BaseEntity implements \Iterator
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
	/** @Column(type="string", length=50, unique=true) */
        $login,
        /** @Column(type="string", length=50, nullable=true) */
        $name,
        /** @Column(type="string", length=60) */
        $password,
	/** @Column(type="string", length=255) */    
	$email,
        /**
         * @ManyToOne(targetEntity="UserRole")
         */
        $role,
	/** @Column(type="datetime") */    
	$created
    ;

    private
        $position = 0,
        $properties = array(
            0 => 'id', 1 => 'login', 2 => 'name', 3 => 'password', 4 => 'email', 5 => 'role'
        )
    ;

    public function getId() 
    {
	return $this->id;
    }
        
    public function getLogin() 
    {
	return $this->login;
    }

    public function setLogin($login) 
    {
	$this->login = $login;
	return $this;
    }
        
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
    
    public function getEmail() 
    {
	return $this->email;
    }

    public function setEmail($email) 
    {
	$this->email = $email;
    }
        
    public function getRole() 
    {
        return $this->role;
    }

    public function setRole(UserRole $role) 
    {
        $this->role = $role;
        return $this;
    }

    /** @PrePersist */
    public function onPrePersist()
    {
	$this->created = new \DateTime;
	$hasher = new PasswordHasher();
	$this->password = $hasher->hashPassword($this->password);
    }
}