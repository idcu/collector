<?php
namespace Common\FormBundle\Utility;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Common\MediaBundle\Entity\Media;

class Upload
{
    protected $mediaManager;

    public function __construct($mediaManager) {
        $this->mediaManager = $mediaManager;
    }


    public function process($attach)
    {
        foreach ($attach as $data)
        {
            if ($data instanceof Media)
            {
                if (!is_null($data->getId()))
                {
                    $mediaManager = $this->mediaManager;
                    $media = $mediaManager->findOneBy(array('id' => $data->getId()));
                    $data->setAuthorName($media->getAuthorName());
                    $data->setCdnFlushAt($media->getCdnFlushAt());
                    $data->setCdnIsFlushable($media->getCdnIsFlushable());
                    $data->setCdnStatus($media->getCdnStatus());
                    $data->setContentType($media->getContentType());
                    $data->setContext($media->getContext());
                    $data->setCopyright($media->getCopyright());
                    $data->setCreatedAt($media->getCreatedAt());
                    $data->setDescription($media->getDescription());
                    $data->setEnabled($media->getEnabled());
                    $data->setHeight($media->getHeight());
                    $data->setWidth($media->getWidth());
                    $data->setLength($media->getLength());
                    $data->setName($media->getName());
                    $data->setProviderName($media->getProviderName());
                    $data->setProviderReference($media->getProviderReference());
                    $data->setProviderMetadata($media->getProviderMetadata());
                    $data->setProviderStatus($media->getProviderStatus());
                    $data->setSize($media->getSize());
                }
            }
            else
            {
                if (!is_null($data))
                    $this->process($data);
            }
        }
    }

    public function getData($data)
    {
        $id = null;
        if (!is_null($data))
        {
            if (is_array($data)){
                if (isset($data['id'])){
                    $id = $data['id'];
                }
            }
            elseif(is_object($data)){
                $id = $data->getId();
                if (property_exists($data, 'delete') && $data->getDelete())
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
            if (!is_null($id))
            {
                $mediaManager = $this->mediaManager;
                return $mediaManager->findOneBy(array('id' => $id));
            }
        }
        return null;
    }

    public function getAttachData($attach)
    {
        $attachData = array();
        foreach ($attach as $key => $data)
        {
            if ($data instanceof Media)
            {
                $attachData[$key]['id'] = $data->getId();
                $attachData[$key]['tmpId'] = $data->getTmpId();
            }
            else
            {
                if ($data!=null)
                    $attachData[$key] = $this->getAttachData($data);
            }
        }
        return $attachData;
    }
}
