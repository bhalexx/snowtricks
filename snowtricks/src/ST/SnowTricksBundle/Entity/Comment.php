<?php

namespace ST\SnowTricksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ST\UserBundle\Entity\User;
use ST\SnowTricksBundle\Entity\Trick;

/**
 * Comment
 *
 * @ORM\Table(name="st_comment")
 * @ORM\Entity(repositoryClass="ST\SnowTricksBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="ST\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @var Trick
     *
     * @ORM\ManyToOne(targetEntity="ST\SnowTricksBundle\Entity\Trick", cascade={"persist"},inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     * 
     * @Assert\NotBlank(message="Vous n'avez rien à dire de cette figure ?")
     */
    private $content;

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

    public function __construct()
    {
        $this->dateInsert = new \DateTime();
    }


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
     * Set author
     *
     * @param User $author
     *
     * @return Comment
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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set dateInsert
     *
     * @param \DateTime $dateInsert
     *
     * @return Comment
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
     * @return Comment
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
     * Set trick
     *
     * @param Trick $trick
     *
     * @return Comment
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Get trick
     *
     * @return Trick
     */
    public function getTrick()
    {
        return $this->trick;
    }
}
