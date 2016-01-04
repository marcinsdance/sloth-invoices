<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Client
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="clients")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $company_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $contact_name;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=256)
     */
    protected $address;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=30)
     */
    protected $telephone1;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=30)
     */
    protected $telephone2;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=50)
     */
    protected $email1;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=50)
     */
    protected $email2;


    /**
     * @ORM\Column(type="string", length=2)
     */
    protected $language;

    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $currency;

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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Client
     */
    public function setCompanyName($companyName)
    {
        $this->company_name = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     *
     * @return Client
     */
    public function setContactName($contactName)
    {
        $this->contact_name = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contact_name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set telephone1
     *
     * @param string $telephone1
     *
     * @return Client
     */
    public function setTelephone1($telephone1)
    {
        $this->telephone1 = $telephone1;

        return $this;
    }

    /**
     * Get telephone1
     *
     * @return string
     */
    public function getTelephone1()
    {
        return $this->telephone1;
    }

    /**
     * Set telephone2
     *
     * @param string $telephone2
     *
     * @return Client
     */
    public function setTelephone2($telephone2)
    {
        $this->telephone2 = $telephone2;

        return $this;
    }

    /**
     * Get telephone2
     *
     * @return string
     */
    public function getTelephone2()
    {
        return $this->telephone2;
    }

    /**
     * Set email1
     *
     * @param string $email1
     *
     * @return Client
     */
    public function setEmail1($email1)
    {
        $this->email1 = $email1;

        return $this;
    }

    /**
     * Get email1
     *
     * @return string
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * Set email2
     *
     * @param string $email2
     *
     * @return Client
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Client
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return Client
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function __toString()
    {
        return $this->company_name;
    }

    /**
     * Set user
     *
     * @param integer $user
     *
     * @return Client
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }
}
