<?php

namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="Collector\EntityBundle\Repository\CompanyRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Company
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="company_name",type="string", length=255)
     */
    protected $companyName;


    /**
     * @ORM\Column(name="company_name_canonical",type="string", length=255)
     */
    protected $companyNameCanonical;

    /**
     * @ORM\Column(name="company_category",type="array",nullable=true)
     */
    protected $companyCategory;

    /**
     * @ORM\Column(name="company_url",type="string", length=255,nullable= true)
     */
    protected $companyUrl;

    /**
     * @ORM\Column(name="company_code",type="string", length=255,nullable= true)
     */
    protected $companyCode;

    /**
     * プレスリリース
     * @ORM\OneToMany(targetEntity="Press", mappedBy="company", cascade={"all"})
     */
    protected $presses;

    /**
     * @ORM\Column(name="first_publish_date",type="datetime",nullable= true)
     */
    protected $firstPublishDate;

    /**
     * @ORM\Column(name="last_publish_date",type="datetime",nullable= true)
     */
    protected $lastPublishDate;

    /**
     * 配信数
     * @ORM\Column(name="press_count",type="integer",nullable= true)
     */
    protected $pressCount;

    /**
     * 配信の提供元
     * @ORM\ManyToMany(targetEntity="Source", cascade={"persist"} )
     * @ORM\JoinTable(name="companies_sources",
     *      joinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="source_id", referencedColumnName="id")}
     * )
     */
    protected $sources;

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
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        $this->companyNameCanonical = $this->stringFilter($companyName);
    }

    private function stringFilter($string)
    {
        $replace = array(
            '株式会社' => '',
            '有限会社' => '',
            '(株)' => '',
            '(有)' => '',
            '・' => '',
            ' ' => '',
            '　' => ''
        );
        return strtr(strip_tags($string), $replace);
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyNameCanonical
     *
     * @param string $companyNameCanonical
     */
    public function setCompanyNameCanonical($companyNameCanonical)
    {
        $this->companyNameCanonical = $companyNameCanonical;
    }

    /**
     * Get companyNameCanonical
     *
     * @return string
     */
    public function getCompanyNameCanonical()
    {
        return $this->companyNameCanonical;
    }

    /**
     * Set companyCategory
     *
     * @param array $companyCategory
     */
    public function setCompanyCategory($companyCategory)
    {
        $this->companyCategory = $companyCategory;
    }

    /**
     * Get companyCategory
     *
     * @return array 
     */
    public function getCompanyCategory()
    {
        return $this->companyCategory;
    }

    /**
     * Set companyUrl
     *
     * @param string $companyUrl
     */
    public function setCompanyUrl($companyUrl)
    {
        $this->companyUrl = $companyUrl;
    }

    /**
     * Get companyUrl
     *
     * @return string 
     */
    public function getCompanyUrl()
    {
        return $this->companyUrl;
    }

    /**
     * Set companyCode
     *
     * @param string $companyCode
     */
    public function setCompanyCode($companyCode)
    {
        $this->companyCode = $companyCode;
    }

    /**
     * Get companyCompanyCode
     *
     * @return string 
     */
    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    public function __toString()
    {
        if (empty($this->id))
            return "新規の会社";
        return $this->companyName;
    }

    public function __construct()
    {
        $this->setDisabled(false);
        $this->presses = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add presses
     *
     * @param Press $presses
     */
    public function addPress(Press $presses)
    {
        $this->presses[] = $presses;
    }

    /**
     * Get presses
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPresses()
    {
        return $this->presses;
    }

    /**
     * Set presses
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function setPresses($presses)
    {
        $this->presses = $presses;
    }

    /**
     * Add sources
     *
     * @param Source $sources
     */
    public function addSource(Source $sources)
    {
        $this->sources[] = $sources;
    }
    public function removeSource(Source $sources)
    {
        $this->sources->removeElement($sources);
    }

    /**
     * Get sources
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Set sourcees
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function setSources($sources)
    {
        $this->sources = $sources;
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

    public function getFirstPublishDate()
    {
        return $this->firstPublishDate;
    }

    public function getLastPublishDate()
    {
        return $this->lastPublishDate;
    }

    public function getPressCount()
    {
        return $this->pressCount;
    }

    public function setFirstPublishDate($firstPublishDate)
    {
        $this->firstPublishDate = $firstPublishDate;
    }

    public function setLastPublishDate($lastPublishDate)
    {
        $this->lastPublishDate = $lastPublishDate;
    }

    public function setPressCount($pressCount)
    {
        $this->pressCount = $pressCount;
    }

}
