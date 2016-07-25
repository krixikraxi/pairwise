<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="partner")
 */
class Partner
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
    private $partnername;

    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="partner")
     */
    private $bills;

    public function __construct()
    {
        $this->bills = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPartnername() {
        return $this->partnername;
    }

    /**
     * @param mixed $partnername
     */
    public function setPartnername($partnername) {
        $this->partnername = $partnername;
    }

    /**
     * @return mixed
     */
    public function getBills() {
        return $this->bills;
    }

    /**
     * @param mixed $bills
     */
    public function setBills($bills) {
        $this->bills = $bills;
    }


}