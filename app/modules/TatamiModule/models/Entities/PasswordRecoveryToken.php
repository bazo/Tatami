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
         */
        $user,
	/** @Column(type="datetime") */    
	$created
    ;
    
    public function getToken() 
    {
	return $this->token;
    }

    public function setUser($user) 
    {
	$this->user = $user;
	return $this;
    }
        
    public function getUser() 
    {
	return $this->user;
    }

    public function getCreated() 
    {
	return $this->created;
    }

        
    /** @PrePersist */
    public function onPrePersist()
    {
	$this->token = uniqid(sha1($this->user->login));
	$this->created = new \DateTime;
    }
}