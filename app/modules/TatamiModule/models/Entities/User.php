<?php

namespace Entity;

/** @Entity */
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
        /** @Column(type="string", length=40, nullable=false) */
        $password,
	/** @Column(type="string", length=255) */    
	$email,
        /**
         * @OneToOne(targetEntity="UserRole")
         */
        $role
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
        $this->password = \sha1($password);
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

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        $func = 'get'.\ucfirst($this->properties[$this->position]);
        return $this->$func();
    }

    public function key() {
        return $this->properties[$this->position];
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->properties[$this->position]);
    }
}