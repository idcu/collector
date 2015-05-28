<?php
namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="press_image_file")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PressImageFile {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\ManyToOne(targetEntity="Press", inversedBy="imageFiles")
     */
    protected $press;

    /**
     * @ORM\ManyToOne(targetEntity="Common\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="image_file_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $imageFile;

    /**
     * @ORM\Column(name="image_file_url",type="string", length=255, nullable=true)
     */
    protected $imageFileUrl;

    /**
     * @ORM\Column(name="image_file_title",type="string", length=255, nullable=true)
     */
    protected $imageFileTitle;

    /**
     * @ORM\Column(name="image_file_source",type="string", length=255, nullable=true)
     */
    protected $imageFileSource;

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
     * Set imageFileUrl
     *
     * @param string $imageFileUrl
     */
    public function setImageFileUrl($imageFileUrl)
    {
        $this->imageFileUrl = $imageFileUrl;
    }

    /**
     * Get imageFileUrl
     *
     * @return string
     */
    public function getImageFileUrl()
    {
        return $this->imageFileUrl;
    }


    /**
     * Set imageFileTitle
     *
     * @param string $imageFileTitle
     */
    public function setImageFileTitle($imageFileTitle)
    {
        $this->imageFileTitle = $imageFileTitle;
    }

    /**
     * Get imageFileTitle
     *
     * @return string 
     */
    public function getImageFileTitle()
    {
        return $this->imageFileTitle;
    }

    /**
     * Set imageFileSource
     *
     * @param string $imageFileSource
     */
    public function setImageFileSource($imageFileSource)
    {
        $this->imageFileSource = $imageFileSource;
    }

    /**
     * Get imageFileSource
     *
     * @return string
     */
    public function getImageFileSource()
    {
        return $this->imageFileSource;
    }

    /**
     * Set press
     *
     * @param Collector\EntityBundle\Entity\Press $press
     */
    public function setPress(\Collector\EntityBundle\Entity\Press $press)
    {
        $this->press = $press;
    }

    /**
     * Get press
     *
     * @return Collector\EntityBundle\Entity\Press 
     */
    public function getPress()
    {
        return $this->press;
    }

    /**
     * Set imageFile
     *
     * @param Common\MediaBundle\Entity\Media $imageFile
     */
    public function setImageFile(\Common\MediaBundle\Entity\Media $imageFile)
    {
        $this->imageFile = $imageFile;
    }

    /**
     * Get imageFile
     *
     * @return Common\MediaBundle\Entity\Media 
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
}