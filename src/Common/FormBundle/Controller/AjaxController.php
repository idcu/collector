<?php
namespace Common\FormBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedException;

class AjaxController extends Controller
{
    /**
     * @Route("/ajax/image_upload",name="ajax_image_upload",requirements={"_method"="POST"})
     */
    public function imageUploadAction()
    {
        $context = $this->getRequest()->request->get('context');
        $providerName = $this->getRequest()->request->get('providerName');
        $tmpId = $this->getRequest()->request->get('tmpId');
        $file = $this->getRequest()->files->get('imageFile');
        $mediaManager = $this->get('sonata.media.manager.media');
        if ($tmpId>0)
            $media = $mediaManager->findOneBy(array('id' => $tmpId));
        else
            $media = $mediaManager->create();
        $media->setBinaryContent($file);
        $media->setContext($context);
        $media->setProviderName($providerName);
        $mediaManager->save($media);
        if (is_null($media->getId())) throw new \ErrorException;
        $data['id'] = $media->getId();
        $data['tmpId'] = $media->getId();
        $data['title'] = $media->getName();
        $pool =  $this->get('sonata.media.pool');
        $provider =$pool->getProvider($media->getProviderName());
        $format = $provider->getFormatName($media, "small");
        $data['url'] = $provider->generatePublicUrl($media, $format);
        $headers = array(
            'Content-Type'          => 'application/json; charset=utf-8'
        );
        return new Response(json_encode($data),200,$headers);
    }

    /**
     * @Route("/ajax/attach_upload",name="ajax_attach_upload")
     */
    public function attachUploadAction()
    {
        $logger = $this->get('logger');
        $logger->debug("test1");
        $logger->debug("request: ". implode(" ",$this->getRequest()->request->all()));
        $webClipId = 1;
        $em = $this->getDoctrine()->getManager();
        $method = $this->getRequest()->getMethod();
        if($method == 'GET'){
            //$webClip = $em->getRepository("AtclippingEntityBundle:WebClip")->findOneBy(array('id' => $webClipId));
            //$staffResultFiles = $webClip->getStaffResultFiles();
            //$mediaManager = $this->get('sonata.media.manager.media');
            $result = array();
//            foreach($staffResultFiles as $key=>$val){
//                $tempMedia = $mediaManager->findOneBy(array('id'=>$val->getId()));
//                $result['files'][$key]['name'] = $tempMedia->getName();
//                $result['files'][$key]['size'] = 0;
//                $result['files'][$key]['url'] = $this->generateUrl('ajax_attach_download',array('id' => $val->getId()));;
//                $result['files'][$key]['thumbnailUrl'] = '';
//                $result['files'][$key]['deleteUrl'] = $this->generateUrl('ajax_attach_upload',array('media_id'=>$val->getId()));
//                $result['files'][$key]['deleteType'] = 'DELETE';
//            }
        }elseif($method == 'DELETE'){
            echo $method;
            exit;
        }elseif($method == 'POST'){
            $logger = $this->get('logger');
            $logger->debug("post");
            $logger->debug("request: ". implode(" ",$this->getRequest()->request->all()));
            $result = $this->save_media();
            //$key = 0;
//            $result['files'][$key]['name'] = 'test';
//            $result['files'][$key]['size'] = 0;
//            $result['files'][$key]['url'] = 'http://www.';
//            $result['files'][$key]['thumbnailUrl'] = '';
//            $result['files'][$key]['deleteUrl'] = $this->generateUrl('ajax_attach_upload',array('media_id'=>1));
//            $result['files'][$key]['deleteType'] = 'DELETE';
//            echo json_encode($result);
//            exit;
            //$logger->debug("result: ". implode(" ",$result));
        }

        //print_r($result);


        $headers = array(
            'Content-Type'          => 'application/json; charset=utf-8'
        );
        return new Response(json_encode($result),200,$headers);
    }

    /**
     * @Route("/ajax/attach_download/{id}",name="ajax_attach_download")
     */
    public function downloadAction($id, $format = 'reference')
    {
        $mediaManager = $this->get('sonata.media.manager.media');
        $media = $mediaManager->findOneBy(array('id' => $id));
        if (!$media) {
            throw new NotFoundHttpException(sprintf('unable to find the media with the id : %s', $id));
        }
        $pool =  $this->get('sonata.media.pool');
        $provider =$pool->getProvider($media->getProviderName());
        $headers = array(
            'Content-Type'          => $media->getContentType(),
            'Content-Disposition'   => sprintf('attachment; filename="%s"', $media->getMetadataValue('filename')),
        );
        $content = $this->get('buzz')->get(str_replace('https','http',$provider->generatePublicUrl($media, $format)))->getContent();
        return new Response($content, 200, $headers);
    }

    /**
     * @Route("/ajax/remove_media",name="ajax_remove_attach")
     */
    public function removeAttachAction(){
        //print_r($this->getRequest());
        $request = $this->getRequest();
        $id = $request->get('id');
        $entity = $request->get('entity');
        $entityId = $request->get('entity_id');
        $yearMonth = $request->get('year_month');
        $key = $request->get('key');
        $this->removeMedia($id);
        $em = $this->getDoctrine()->getManager();
        $entityObj = $em->getRepository("$entity")->findOneBy(array('id'=>$entityId));
        $mediaData = array();
        $mediaData = $entityObj->getUploadMediaData();
        unset($mediaData[$yearMonth][$key]);
        $entityObj->setUploadMediaData($mediaData);
        try{
            $em->persist($entityObj);
            $em->flush();
            $flag = 1;
        }catch(\Doctrine\ORM\EntityNotFoundException $e){
            $flag = 0;
        }

        return new Response($flag, 200);

    }

    public function removeMedia($id){
        $mediaManager = $this->get('sonata.media.manager.media');
        $media = $mediaManager->findOneBy(array('id' => $id));
        $mediaManager->delete($media);
    }

    public function save_media(){
        //$context = $this->getRequest()->request->get('context');
        //$providerName = $this->getRequest()->request->get('providerName');
        $logger = $this->get('logger');
		$result = array();
		$context = $this->getRequest()->get('context');
		$providerName = $this->getRequest()->get('providerName');
        $yearMonth = $this->getRequest()->get('upload_year_month');
        //$month = $this->getRequest()->get('upload_month');
        $entity = $this->getRequest()->get('entity');
        $uploadObjectId = $this->getRequest()->get('upload_object_id');
        $em = $this->getDoctrine()->getManager();
        $mediaManager = $this->get('sonata.media.manager.media');
        $uploadObj = $em->getRepository("$entity")->findOneBy(array('id'=>$uploadObjectId));
        $mediaData = array();
        $mediaData = $uploadObj->getUploadMediaData();
        //print_R($mediaData);exit;
        $flag = true;
        $media = $mediaManager->create();
        //$file = $this->getRequest()->files->get('attachFile');
        $file = $this->getRequest()->files->get('files');

        foreach($file as $key=>$val){
            $logger->debug("file: ".$key. print_r($val,true));
            $media->setBinaryContent($val);
            $media->setContext($context);
            $media->setProviderName($providerName);
            $mediaManager->save($media);
            if (is_null($media->getId())) throw new \ErrorException;
            $result['files'][$key]['name'] = $media->getName();
            //$result['files'][$key]['size'] = 0;
            $result['files'][$key]['url'] = $this->generateUrl('ajax_attach_download',array('id'=>$media->getId()));
            //$result['files'][$key]['thumbnailUrl'] = '';
            //$result['files'][$key]['deleteUrl']='';//$this->generateUrl('ajax_attach_upload',array('media_id'=>$media->getId()));
            //$result['files'][$key]['deleteType'] = 'DELETE';
            $result['files'][$key]['media_id'] = $media->getId();
            $result['files'][$key]['year_month'] = $yearMonth;
            //$result['url'] = $this->generateUrl('ajax_attach_download',array('id' => $media->getId()));
            $mediaData[$yearMonth][] = array('id'=> $media->getId(),'name'=>$media->getName());//$media->getId();
            $tempArr = array_keys($mediaData[$yearMonth]);
            $result['files'][$key]['key'] = array_pop($tempArr);
        }
        if($flag){
            $uploadObj->setUploadMediaData($mediaData);
            $em->persist($uploadObj);
            $em->flush();
        }


        return $result;



    }

}