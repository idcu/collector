<?php
namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;
use Collector\EntityBundle\Utils\Converter;

/**
 * @ORM\Table(name="press")
 * @ORM\Entity(repositoryClass="Collector\EntityBundle\Repository\PressRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Press {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    protected $subtitle;

    /**
     * 内容(Html)
     * @ORM\Column(type="text")
     */
    protected $content;


    /**
     * 内容(Text)
     * @ORM\Column(type="text")
     */
    protected $contentText;

    /**
     * リリース日
     * @ORM\Column(name="publish_date",type="datetime")
     */
    protected $publishDate;


    /**
     * Url
     * @ORM\Column(type="string", length=255)
     */
    protected $pressUrl;

    /**
     * 提供元
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="presses")
     */
    protected $source;

    /**
     * 企業
     *@ORM\ManyToOne(targetEntity="Company", inversedBy="presses")
     */
    protected $company;

    /**
     * 関連画像
     * @ORM\OneToMany(targetEntity="PressImage", mappedBy="press", cascade={"persist"})
     */
    protected $images;

    /**
     * 関連ファイル
     * @ORM\OneToMany(targetEntity="PressFile", mappedBy="press", cascade={"persist"})
     */
    protected $files;
    
    /**
     * 関連画像ファイル
     * @ORM\OneToMany(targetEntity="PressImageFile", mappedBy="press", cascade={"persist"})
     */
    protected $imageFiles;


    protected $genres;


    /**
     * @ORM\Column(name="created_at",type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="updated_at",type="datetime")
     */
    protected $updatedAt;

    /**
     * 有効／無効
     * @ORM\Column(type="boolean")
     */
    protected $disabled;



    public function __toString() {
        if (empty($this->id)) return "新規のプレスリリース";
        return $this->pressUrl;
    }

    public function __construct()
    {
        $this->setDisabled(false);
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
        $this->imageFiles = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set subtitle
     *
     * @param string $subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set contentText
     *
     * @param text $contentText
     */
    public function setContentText($contentText)
    {
        $this->contentText = $contentText;
    }

    /**
     * Get contentText
     *
     * @return text
     */
    public function getContentText()
    {
        return $this->contentText;
    }

    /**
     * Set pressUrl
     *
     * @param string $pressUrl
     */
    public function setPressUrl($pressUrl)
    {
        $this->pressUrl = $pressUrl;
    }

    /**
     * Get pressUrl
     *
     * @return string
     */
    public function getPressUrl()
    {
        return $this->pressUrl;
    }
    
    /**
     * Add images
     *
     * @param PressImage $images
     */
    public function addPressImage(PressImage $image)
    {
        $this->images[] = $image;
    }

    /**
     * Get images
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add files
     *
     * @param PressFile $files
     */
    public function addPressFile(PressFile $file)
    {
        $this->files[] = $file;
    }

    /**
     * Get files
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }
    /**
     * Add imagefile
     *
     * @param PressImageFile $imagefile
     */
    public function addPressImageFile(PressImageFile $imageFile)
    {
        $this->imageFiles[] = $imageFile;
    }

    /**
     * Get imagefile
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getImageFiles()
    {
        return $this->imageFiles;
    }
    /**
     * Set publishDate
     *
     * @param datetime $publishDate
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
    }

    /**
     * Get publishDate
     *
     * @return datetime 
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }


    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set company
     *
     * @param Company $company
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function getTextToSearch()
    {
        $text = $this->title." ".$this->subtitle." ".$this->contentText;
//        $text = strip_tags($text);
        $text =  Converter::convertText($text);
        return $text;
    }

    /**
     * Get source
     *
     * @return array 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set source
     *
     *  @param Source $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    public function getSourceRank(){
        if (is_null($this->source)) return 0;
        return $this->source->getSourceRank();
    }

    public function getPublishDateToSearch(){
        return date_format($this->publishDate, 'Y-m-d');
    }

    public function getHasImage(){
        if (sizeof($this->images)>0) return true;
        return false;
    }

    public function getDisabledToSearch(){
        if ($this->disabled||$this->company->getDisabled()) return true;
        return false;
    }

    /**
     * Set disabled
     *
     * @param smallint $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * Get disabled
     *
     * @return smallint
     */
    public function getDisabled()
    {
        return $this->disabled;
    }
}