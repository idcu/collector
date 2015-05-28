<?php
namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="press_file")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class PressFile {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\ManyToOne(targetEntity="Press", inversedBy="files")
     */
    protected $press;

    /**
     * @ORM\ManyToOne(targetEntity="Common\MediaBundle\Entity\Media", cascade={"all"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $file;

    /**
     * @ORM\Column(name="file_url",type="string", length=255, nullable=true)
     */
    protected $fileUrl;

    /**
     * @ORM\Column(name="file_title",type="string", length=255, nullable=true)
     */
    protected $fileTitle;

    /**
     * @ORM\Column(name="file_source",type="string", length=255, nullable=true)
     */
    protected $fileSource;

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
     * Set fileUrl
     *
     * @param string $fileUrl
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * Get fileUrl
     *
     * @return string
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }


    /**
     * Set fileTitle
     *
     * @param string $fileTitle
     */
    public function setFileTitle($fileTitle)
    {
        $this->fileTitle = $fileTitle;
    }

    /**
     * Get fileTitle
     *
     * @return string 
     */
    public function getFileTitle()
    {
        return $this->fileTitle;
    }

    /**
     * Set fileSource
     *
     * @param string $fileSource
     */
    public function setFileSource($fileSource)
    {
        $this->fileSource = $fileSource;
    }

    /**
     * Get fileSource
     *
     * @return string
     */
    public function getFileSource()
    {
        return $this->fileSource;
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
     * Set file
     *
     * @param Common\MediaBundle\Entity\Media $file
     */
    public function setFile(\Common\MediaBundle\Entity\Media $file)
    {
        $this->file = $file;
    }

    /**
     * Get file
     *
     * @return Common\MediaBundle\Entity\Media 
     */
    public function getFile()
    {
        return $this->file;
    }
}