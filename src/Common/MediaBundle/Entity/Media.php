<?php
namespace Common\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;

/**
 * Common\MediaBundle\Entity\Media
 *
 * @ORM\Table(name = "media__resource")
 * @ORM\Entity()
 */
class Media extends BaseMedia
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $tmpId;

    protected $delete;

    protected $uploadFile;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTmpId()
    {
        return $this->tmpId;
    }

    public function setTmpId($tmpId)
    {
        $this->tmpId = $tmpId;
    }

    public function setDelete($delete)
    {
        $this->delete = $delete;
    }

    public function getDelete()
    {
        return $this->delete;
    }

    public function setUploadFile($uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    public function getUploadFile()
    {
        return $this->uploadFile;
    }

}
