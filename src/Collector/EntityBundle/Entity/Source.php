<?php
namespace Collector\EntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Common\FormBundle\Validator as MyAssert;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Table(name="source")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Source {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * 提供元名
     * @ORM\Column(type="string", length=255)
     */
    protected $sourceName;

    /**
     * 提供元Url
     * @ORM\Column(type="string", length=255)
     */
    protected $sourceUrl;

    /**
     * 提供元ランク
     * @ORM\Column(type="integer")
     */
    protected $sourceRank;

    /**
     * プレスリリース
     * @ORM\OneToMany(targetEntity="Press", mappedBy="company", cascade={"all"})
     */
    protected $presses;



    public function __toString() {
        if (empty($this->id)) return "新規の提供元";
        return $this->sourceName;
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
     * Set sourceName
     *
     * @param string $sourceName
     */
    public function setSourceName($sourceName)
    {
        $this->sourceName = $sourceName;
    }

    /**
     * Get sourceName
     *
     * @return string 
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * Set sourceUrl
     *
     * @param string $sourceUrl
     */
    public function setSourceUrl($sourceUrl)
    {
        $this->sourceUrl = $sourceUrl;
    }

    /**
     * Get sourceUrl
     *
     * @return string 
     */
    public function getSourceUrl()
    {
        return $this->sourceUrl;
    }

    /**
     * Set sourceRank
     *
     * @param integer $sourceRank
     */
    public function setSourceRank($sourceRank)
    {
        $this->sourceRank = $sourceRank;
    }

    /**
     * Get sourceRank
     *
     * @return integer
     */
    public function getSourceRank()
    {
        return $this->sourceRank;
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
}