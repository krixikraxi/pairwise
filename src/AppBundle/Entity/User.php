<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partnerone_id", referencedColumnName="id", nullable=false)
     */
    private $partnerone;

    /**
     * @ORM\OneToOne(targetEntity="Partner")
     * @ORM\JoinColumn(name="partnertwo_id", referencedColumnName="id", nullable=false)
     */
    private $partnertwo;


    /**
     * Get id
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPartnerone() {
        return $this->partnerone;
    }

    /**
     * @param mixed $partnerone
     */
    public function setPartnerone($partnerone) {
        $this->partnerone = $partnerone;
    }

    /**
     * @return mixed
     */
    public function getPartnertwo() {
        return $this->partnertwo;
    }

    /**
     * @param mixed $partnertwo
     */
    public function setPartnertwo($partnertwo) {
        $this->partnertwo = $partnertwo;
    }
}
