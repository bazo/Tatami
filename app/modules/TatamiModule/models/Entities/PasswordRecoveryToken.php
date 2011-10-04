<?php
namespace Entity;
/** 
 * @Entity(repositoryClass="Tatami\Models\Repositories\PasswordRecoveryTokenRepository") 
 * @HasLifecycleCallbacks
 */
class PasswordRecoveryToken extends BaseEntity
{
    protected
        
	/** @Column(type="string", length=53, unique=true) */
        $token,
	    
        /**
         * @ManyToOne(targetEntity="User")
	 * @var User
         */
        $user,
	    
	/** @Column(type="datetime") */    
	$created,
	    
	/** @Column(type="boolean") */    
	$used
    ;
    
    public function __construct()
    {
	$this->used = false;
    }

    public function getToken() 
    {
	return $this->token;
    }

    public function setUser(User $user) 
    {
	$this->user = $user;
	return $this;
    }
    
    /**
     *
     * @return User
     */
    public function getUser() 
    {
	return $this->user;
    }

    public function getCreated() 
    {
	return $this->created;
    }

    public function getUsed() 
    {
	return $this->used;
    }

    public function setUsed($used) 
    {
	$this->used = $used;
	return $this;
    }

            
    /** @PrePersist */
    public function onPrePersist()
    {
	$this->token = uniqid(sha1($this->user->email));
	$this->created = new \DateTime;
    }
}