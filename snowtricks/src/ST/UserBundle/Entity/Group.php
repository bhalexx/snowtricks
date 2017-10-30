<?php

	namespace ST\UserBundle\Entity;

	use FOS\UserBundle\Model\Group as BaseGroup;
	use Symfony\Component\Validator\Constraints as Assert;
	use Doctrine\ORM\Mapping as ORM;
	use ST\UserBundle\Entity\User;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="st_group")
	 */
	class Group extends BaseGroup
	{
	    /**
	     * @ORM\Id
	     * @ORM\Column(type="integer")
	     * @ORM\GeneratedValue(strategy="AUTO")
	     */
	    protected $id;

	    protected $roles;

	    /**
	     * @ORM\ManyToMany(targetEntity="ST\UserBundle\Entity\User", mappedBy="groups")
	     */
	    protected $users;

	    /**
	     * @var string
	     *
	     * @ORM\Column(type="string", length=255, unique=true)
	     * @Assert\NotBlank(message="Le nom du groupe d'utilisateur ne peut être vide.")
	     */
	    protected $label;

	    public function __construct($roles = array())
	    {
	        $this->roles = $roles;
	    }

	    /**
	     * Get id
	     *
	     * @return integer 
	     */
	    public function getId()
	    {
	        return $this->id;
	    }

	
	    /**
	     * Add user
	     *
	     * @param User $user
	     *
	     * @return Group
	     */
	    public function addUser(User $user)
	    {
	        $this->users[] = $user;
	        $user->addGroup($this);

	        return $this;
	    }

	    /**
	     * Remove user
	     *
	     * @param User $user
	     */
	    public function removeUser(User $user)
	    {
	        $this->users->removeElement($user);
	    }

	    /**
	     * Get users
	     *
	     * @return \Doctrine\Common\Collections\Collection
	     */
	    public function getUsers()
	    {
	        return $this->users;
	    }

	    /**
	     * Set label
	     * @param String $label
	     */
	    public function setLabel($label)
	    {
	    	$this->label = $label;
	    }

	    /**
	     * Get label
	     * @return String $label
	     */
	    public function getLabel()
	    {
	    	return $this->label;
	    }
	}