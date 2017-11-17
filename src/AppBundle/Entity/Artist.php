<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Artwork;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="artist")
 */
class Artist
{
    /**
     * @ORM\Column(type="string", length=45)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function __construct()
    {
        $this->artworks = new ArrayCollection();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Artwork", mappedBy="artist", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"description" = "ASC"})
     */
    protected $artworks;


    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Artist
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Artist
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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

    public function addArtwork(Artwork $artwork)
    {
        $this->artworks[] = $artwork;
        $artwork->setArtist($this);

        return $this;
    }

    public function removeArtwork(Artwork $artwork)
    {
        $this->artworks->removeElement($artwork);
    }
}

