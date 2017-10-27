<?php

namespace ST\SnowTricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use ST\SnowTricksBundle\Entity\Trick;

/**
 * Family
 *
 * @ORM\Table(name="st_family")
 * @ORM\Entity(repositoryClass="ST\SnowTricksBundle\Repository\FamilyRepository")
 */
class Family
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Le nom de la famille ne peut être vide.")
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Décrivez la famille en quelques mots.")
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="ST\SnowTricksBundle\Entity\Trick", cascade={"persist", "remove"}, mappedBy="family")
     */
    private $tricks;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Family
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Family
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Family
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    /**
     * Get tricks
     *
     * @return array
     */
    public function getTricks()
    {
        return $this->tricks;
    }

    /**
     * Add trick
     *
     * @param Trick $trick
     *
     * @return Family
     */
    public function addTrick(Trick $trick)
    {
        $this->tricks[] = $trick;

        return $this;
    }

    /**
     * Remove trick
     *
     * @param Trick $trick
     */
    public function removeTrick(Trick $trick)
    {
        $this->tricks->removeElement($trick);
    }
}
