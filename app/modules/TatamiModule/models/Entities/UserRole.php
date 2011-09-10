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
            
        /** @Column(type="string", length=50, nullable=false) */
        $name,
            
        /**
         * @ManyToOne(targetEntity="UserRole", inversedBy="children")
         * @JoinColumn(name="parent_id", referencedColumnName="id")
         */
        $parent = null,
          
        /**
          * @OneToMany(targetEntity="UserRole", mappedBy="parent")
          */    
        $children,
            
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
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getParent() 
    {
        return $this->parent;
    }

    public function setParent(UserRole $parent) 
    {
        $this->parent = $parent;
        return $this;
    }

    public function getChildren()
    {
        return $this->children;
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
}