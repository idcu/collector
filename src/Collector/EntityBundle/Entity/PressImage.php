<?php
namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="press_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PressImage {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\ManyToOne(targetEntity="Press", inversedBy="images")
     */
    protected $press;

    /**
     * @ORM\ManyToOne(targetEntity="Common\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $image;

    /**
     * @ORM\Column(name="image_url",type="string", length=255, nullable=true)
     */
    protected $imageUrl;

    /**
     * @ORM\Column(name="image_title",type="string", length=255, nullable=true)
     */
    protected $imageTitle;

    /**
     * @ORM\Column(name="image_source",type="string", length=255, nullable=true)
     */
    protected $imageSource;

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
     * Set imageUrl
     *
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }


    /**
     * Set imageTitle
     *
     * @param string $imageTitle
     */
    public function setImageTitle($imageTitle)
    {
        $this->imageTitle = $imageTitle;
    }

    /**
     * Get imageTitle
     *
     * @return string 
     */
    public function getImageTitle()
    {
        return $this->imageTitle;
    }

    /**
     * Set imageSource
     *
     * @param string $imageSource
     */
    public function setImageSource($imageSource)
    {
        $this->imageSource = $imageSource;
    }

    /**
     * Get imageSource
     *
     * @return string
     */
    public function getImageSource()
    {
        return $this->imageSource;
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
     * Set image
     *
     * @param Common\MediaBundle\Entity\Media $image
     */
    public function setImage(\Common\MediaBundle\Entity\Media $image)
    {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return Common\MediaBundle\Entity\Media 
     */
    public function getImage()
    {
        return $this->image;
    }
}