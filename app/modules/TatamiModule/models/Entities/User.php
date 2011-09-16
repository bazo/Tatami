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
	    
        /** 
	 * @Column(type="string", length=100)
	 * @NotBlank
	 */
        $name,
	    
        /** @Column(type="string", length=100) */
        $password,
	    
	/** @Column(type="string", length=255, unique=true) */    
	$email,
	    
        /**
         * @ManyToOne(targetEntity="UserRole", cascade={"persist", "remove"})
         */
        $role,
	/** @Column(type="datetime") */    
	$created
    ;

    public function getId() 
    {
	return $this->id;
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
	$hasher = new PasswordHasher();
        $this->password = $hasher->hashPassword($password);
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
	if($this->password == null) $this->password = $this->generateRandomPassword();
	$this->created = new \DateTime;
    }
    
    private function generateRandomPassword($length = 8)
    {
	$chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%^&*()_+[];,./';
	$password = '';
	$max = strlen($chars) - 1;
	for($i = 1; $i <= $length; $i++)
	{
	    $pos = rand(0, $max);
	    $char = substr($chars, $pos, 1);
	    $password .= $char;
	}
	return $password;
    }
    
    public function setRoleId($id)
    {
	$this->roleId = $id;
    }
}