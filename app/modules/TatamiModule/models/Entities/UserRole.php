<?php
namespace Entity;

/** @Entity(repositoryClass="Tatami\Models\Repositories\UserRoleRepository") */
class UserRole extends BaseEntity
{
    protected
        /**
         * @Id @Column(type="integer")
         * @GeneratedValue(strategy="AUTO")
         */
        $id,
            
        /** @Column(type="string", length=50, unique="true", nullable=false) */
        $name,
            
        /**
         * @var \Doctrine\Common\Collections\ArrayCollection
         * @ManyToMany(targetEntity="Permission", cascade={"persist", "remove"})
         * @JoinTable(name="groups_permissions",
         *      joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
         *      inverseJoinColumns={@JoinColumn(name="permission_id", referencedColumnName="id")}
         *      )
         */
        $permissions
    ;
    
    public function __construct()
    {
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
    
    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
        return $this;
    }
    
    public function getPermissions() 
    {
        return $this->permissions;
    }
    
    public function hasPermission(Permission $permission)
    {
	return $this->permissions->contains($permission);
    }
    
    public function deletePermission($permission)
    {
	$this->permissions->removeElement($permission);
	return $this;
    }
    
    public function deletePermissions($permissions)
    {
	foreach($permissions as $permission)
	{
	    $this->deletePermission($permission);
	}
	return $this;
    }
    
    public function __toString()
    {
	return $this->name;
    }
}