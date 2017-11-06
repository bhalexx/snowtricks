<?php

namespace ST\SnowTricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use ST\SnowTricksBundle\Entity\Picture;
use ST\SnowTricksBundle\Entity\Video;
use ST\SnowTricksBundle\Entity\Comment;
use ST\UserBundle\Entity\User;

/**
 * Trick
 *
 * @ORM\Table(name="st_trick")
 * @ORM\Entity(repositoryClass="ST\SnowTricksBundle\Repository\TrickRepository")
 *
 * @UniqueEntity(fields={"name"}, message="Une figure du même nom existe déjà.")
 */
class Trick
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
     * 
     * @Assert\NotBlank(message="Le nom de la figure ne peut être vide.")
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
     * @ORM\Column(name="description", type="string", length=255)
     * 
     * @Assert\NotBlank(message="Décrivez la figure en quelques mots.")
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="ST\SnowTricksBundle\Entity\Picture", cascade={"persist", "remove"}, mappedBy="trick")
     * 
     * ###@Assert\Valid()
     * ###@Assert\Count(min="1", minMessage="Merci d'ajouter minimum 1 image à la figure")
     */
    private $pictures;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="ST\SnowTricksBundle\Entity\Video", cascade={"persist", "remove"}, mappedBy="trick")
     */
    private $videos;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="ST\SnowTricksBundle\Entity\Comment", cascade={"persist", "remove"}, mappedBy="trick")
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="ST\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="ST\SnowTricksBundle\Entity\Family", cascade={"persist"}, inversedBy="tricks")
     *
     * @Assert\NotNull(message="Un famille ne peut être orpheline. Si le groupe auquel elle appartient n'existe pas, vous pouvez le créer.")
     */
    private $family;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInsert", type="datetime")
     */
    private $dateInsert;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdate", type="datetime", nullable=true)
     */
    private $dateUpdate;


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
     * @return Trick
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
     * @return Trick
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
     * @return Trick
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
     * Set author
     *
     * @param User $author
     *
     * @return Trick
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set family
     *
     * @param string $family
     *
     * @return Trick
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     *
     * @return Trick
     */
    public function setDateInsert($dateInsert)
    {
        $this->dateInsert = $dateInsert;

        return $this;
    }

    /**
     * Get dateInsert
     *
     * @return \DateTime
     */
    public function getDateInsert()
    {
        return $this->dateInsert;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Trick
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateInsert = new \DateTime();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Add picture
     *
     * @param Picture $picture
     *
     * @return Trick
     */
    public function addPicture(Picture $picture)
    {
        $picture->setTrick($this);
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param Picture $picture
     */
    public function removePicture(Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    /**
     * Add video
     *
     * @param Video $video
     *
     * @return Trick
     */
    public function addVideo(Video $video)
    {
        $video->setTrick($this);
        $this->videos[] = $video;

        return $this;
    }

    /**
     * Remove video
     *
     * @param Video $video
     */
    public function removeVideo(Video $video)
    {
        $this->videos->removeElement($video);
    }

    /**
     * Get pictures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures;
    }

    /**
     * Get videos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVideos()
    {
        return $this->videos;
    }

    /**
     * Add comment
     *
     * @param Comment $comment
     *
     * @return Trick
     */
    public function addComment(Comment $comment)
    {
        $comment->setTrick($this);
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
