<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Profile
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="profile")
 */
class Profile
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="profiles")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $company_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $logo_path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $company_registration_number;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $slogan;

    /**
     * @ORM\Column(type="string", length=200)
     */
    protected $footer_note;

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
    protected $telephone;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=50)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=50)
     */
    protected $bank_account;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=20)
     */
    protected $sort_code;

    /**
     * @ORM\Column(type="string", nullable=TRUE, length=100)
     */
    protected $bank_name;

    /**
     * Used with file upload.
     */
    private $temp;

    public function getAbsolutePath()
    {
        return null === $this->logo_path ? null : $this->getUploadRootDir().'/'.$this->logo_path;
    }

    public function getWebPath()
    {
        return null === $this->logo_path
            ? null
            : $this->getUploadDir().'/'.$this->logo_path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (isset($this->logo_path)) {
            $this->temp = $this->logo_path;
            $this->logo_path = null;
        } else {
            $this->logo_path = 'initial';
        }
    }

    /**
     * Gets file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logo_path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->logo_path
        );

        if (isset($this->temp)) {
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Profile
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
     * Set logoPath
     *
     * @param string $logoPath
     *
     * @return Profile
     */
    public function setLogoPath($logoPath)
    {
        $this->logo_path = $logoPath;

        return $this;
    }

    /**
     * Get logoPath
     *
     * @return string
     */
    public function getLogoPath()
    {
        return $this->logo_path;
    }

    /**
     * Set companyRegistrationNumber
     *
     * @param string $companyRegistrationNumber
     *
     * @return Profile
     */
    public function setCompanyRegistrationNumber($companyRegistrationNumber)
    {
        $this->company_registration_number = $companyRegistrationNumber;

        return $this;
    }

    /**
     * Get companyRegistrationNumber
     *
     * @return string
     */
    public function getCompanyRegistrationNumber()
    {
        return $this->company_registration_number;
    }

    /**
     * Set slogan
     *
     * @param string $slogan
     *
     * @return Profile
     */
    public function setSlogan($slogan)
    {
        $this->slogan = $slogan;

        return $this;
    }

    /**
     * Get slogan
     *
     * @return string
     */
    public function getSlogan()
    {
        return $this->slogan;
    }

    /**
     * Set footerNote
     *
     * @param string $footerNote
     *
     * @return Profile
     */
    public function setFooterNote($footerNote)
    {
        $this->footer_note = $footerNote;

        return $this;
    }

    /**
     * Get footerNote
     *
     * @return string
     */
    public function getFooterNote()
    {
        return $this->footer_note;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     *
     * @return Profile
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
     * @return Profile
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Profile
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Profile
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set bankAccount
     *
     * @param string $bankAccount
     *
     * @return Profile
     */
    public function setBankAccount($bankAccount)
    {
        $this->bank_account = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return string
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }

    /**
     * Set sortCode
     *
     * @param string $sortCode
     *
     * @return Profile
     */
    public function setSortCode($sortCode)
    {
        $this->sort_code = $sortCode;

        return $this;
    }

    /**
     * Get sortCode
     *
     * @return string
     */
    public function getSortCode()
    {
        return $this->sort_code;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return Profile
     */
    public function setBankName($bankName)
    {
        $this->bank_name = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Profile
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Profile
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Profile
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}
