<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Persistence\BillRepository")
 * @ORM\Table(name="bill")
 */
class Bill
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
    private $billname;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $billdescription;

    /**
     * @ORM\Column(type="decimal", scale=2)
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $billdate;

    /**
     * @ORM\ManyToOne(targetEntity="Partner", inversedBy="bills")
     * @ORM\JoinColumn(name="partner_id", referencedColumnName="id")
     */
    private $partner;

    /**
     * @ORM\Column(type="boolean")
     */
    private $billed;

    /**
     * @ORM\ManyToOne(targetEntity="Invoice")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $invoice;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBillname() {
        return $this->billname;
    }

    /**
     * @param mixed $billname
     */
    public function setBillname($billname) {
        $this->billname = $billname;
    }

    /**
     * @return mixed
     */
    public function getBilldescription() {
        return $this->billdescription;
    }

    /**
     * @param mixed $billdescription
     */
    public function setBilldescription($billdescription) {
        $this->billdescription = $billdescription;
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getBilldate() {
        return $this->billdate;
    }

    /**
     * @param mixed $billdate
     */
    public function setBilldate($billdate) {
        $this->billdate = $billdate;
    }

    /**
     * @return mixed
     */
    public function getPartner() {
        return $this->partner;
    }

    /**
     * @param mixed $partner
     */
    public function setPartner($partner) {
        $this->partner = $partner;
    }

    /**
     * @return mixed
     */
    public function getBilled() {
        return $this->billed;
    }

    /**
     * @param mixed $billed
     */
    public function setBilled($billed) {
        $this->billed = $billed;
    }

    /**
     * @return mixed
     */
    public function getInvoice() {
        return $this->invoice;
    }

    /**
     * @param mixed $invoice
     */
    public function setInvoice($invoice) {
        $this->invoice = $invoice;
    }
}