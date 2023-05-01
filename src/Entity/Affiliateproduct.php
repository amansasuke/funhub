<?php

namespace App\Entity;

use App\Repository\AffiliateproductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AffiliateproductRepository::class)
 */
class Affiliateproduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $servicename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productprice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $affiliateprice;

    /**
     * @ORM\Column(type="integer")
     */
    private $affiliateuserid;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderuserid;

    /**
     * @ORM\ManyToOne(targetEntity=Affiliate::class, inversedBy="affiliateproducts")
     */
    private $affiliateid;

    /**
     * @ORM\Column(type="date")
     */
    private $adddate;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="affiliateproducts")
     */
    private $orderinfo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commissionpaid;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $paymentdate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductname(): ?string
    {
        return $this->productname;
    }

    public function setProductname(string $productname): self
    {
        $this->productname = $productname;

        return $this;
    }

    public function getServicename(): ?string
    {
        return $this->servicename;
    }

    public function setServicename(string $servicename): self
    {
        $this->servicename = $servicename;

        return $this;
    }

    public function getProductprice(): ?string
    {
        return $this->productprice;
    }

    public function setProductprice(string $productprice): self
    {
        $this->productprice = $productprice;

        return $this;
    }

    public function getAffiliateprice(): ?string
    {
        return $this->affiliateprice;
    }

    public function setAffiliateprice(string $affiliateprice): self
    {
        $this->affiliateprice = $affiliateprice;

        return $this;
    }

    public function getAffiliateuserid(): ?int
    {
        return $this->affiliateuserid;
    }

    public function setAffiliateuserid(int $affiliateuserid): self
    {
        $this->affiliateuserid = $affiliateuserid;

        return $this;
    }

    public function getOrderuserid(): ?int
    {
        return $this->orderuserid;
    }

    public function setOrderuserid(int $orderuserid): self
    {
        $this->orderuserid = $orderuserid;

        return $this;
    }

    public function getAffiliateid(): ?Affiliate
    {
        return $this->affiliateid;
    }

    public function setAffiliateid(?Affiliate $affiliateid): self
    {
        $this->affiliateid = $affiliateid;

        return $this;
    }

    public function getAdddate(): ?\DateTimeInterface
    {
        return $this->adddate;
    }

    public function setAdddate(\DateTimeInterface $adddate): self
    {
        $this->adddate = $adddate;

        return $this;
    }

    public function getExportData()
    {
        return \array_merge([
            'affiliate Name' => $this->affiliateid->getName(),
            'Mobile No' => $this->affiliateid->getPhoneno(),
            'Email' => $this->affiliateid->getEmail(),
            'PAN No.' => $this->affiliateid->getPanno(),
            'GST No.' => $this->affiliateid->getPanno(),
            'State' => $this->affiliateid->getAddress(),
            'UPI ID' => $this->affiliateid->getUpiid(),
            'Bank Name' => $this->affiliateid->getAccountname(),
            'Account No.' => $this->affiliateid->getAccountno(),
            'IFSC' => $this->affiliateid->getIFSC(),
            'Service Purchased through affiliate link' => $this->affiliateid->getEmail(),
            'Date of Purchase' => $this->adddate? $this->adddate->format('Y-m-d'): ' ',
            'Invoice No.' => $this->orderinfo->getId(),
            'Gross Value' => $this->orderinfo->getGrossvalue(),
            'GST Amount' => $this->orderinfo->getGstamount(),
            'Total Value' => $this->orderinfo->getTotalvalue(),
            'Commission Paid' => $this->commissionpaid,
            'Payment Date' =>  $this->paymentdate? $this->paymentdate->format('Y-m-d'): 'pending',
        ]);
    }

    public function getOrderinfo(): ?Order
    {
        return $this->orderinfo;
    }

    public function setOrderinfo(?Order $orderinfo): self
    {
        $this->orderinfo = $orderinfo;

        return $this;
    }

    public function getCommissionpaid(): ?string
    {
        return $this->commissionpaid;
    }

    public function setCommissionpaid(?string $commissionpaid): self
    {
        $this->commissionpaid = $commissionpaid;

        return $this;
    }

    public function getPaymentdate(): ?\DateTimeInterface
    {
        return $this->paymentdate;
    }

    public function setPaymentdate(?\DateTimeInterface $paymentdate): self
    {
        $this->paymentdate = $paymentdate;

        return $this;
    }
}
