<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Profile
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM|HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=100)
     */
    protected $company_name;

    /**
     * @ORM|Column(type="string", length=255, nullable=true)
     */
    protected $logo_path;

    /**
     * @Assert|File(maxSize="6000000")
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
     * @ORM|Column(type="string", nullable=TRUE, length=50)
     */
    protected $bank_account;

    /**
     * @ORM|Column(type="string", nullable=TRUE, length=20)
     */
    protected $sort_code;

    /**
     * @ORM|Column(type="string", nullable=TRUE, length=100)
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
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
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
     * @ORM|PrePersist()
     * @ORM|PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->logo_path = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM|PostPersist()
     * @ORM|PostUpdate()
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
     * @ORM|PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
}
