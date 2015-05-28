<?php

namespace Collector\EntityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Collector\EntityBundle\Entity\Company;
use Collector\EntityBundle\Entity\Press;
use Collector\EntityBundle\Entity\PressImage;
use Collector\EntityBundle\Entity\PressFile;
use Collector\EntityBundle\Entity\PressImageFile;
use FOS\RestBundle\Controller\Annotations as Rest;

class RestController extends Controller
{

    public function getCompaniesAction()
    {
        
    }

    public function postCompaniesAction(Request $request)
    {
        try {
            $values = json_decode($request->getContent());
            $em = $this->get('doctrine')->getEntityManager();
            foreach ($values as $value) {
                $company = new Company();
                $company->setName($value->company_name);
                //            if (property_exists($value, 'press_content'))
                //                $press->setContent($value->press_content);
                $em->persist($company);
            }
            $em->flush();
            $result["status"] = "ok";
            $result["message"] = sizeof($values) . " companies added";
        } catch (Exception $e) {
            $result["status"] = "error";
            $result["message"] = toString($e);
        }
    }

    /**
     * @Rest\View
     */
    public function getPressesAction(Request $request)
    {
        if ($request->query->has('id')) {
            $em = $this->get('doctrine')->getEntityManager();
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            $press = $pressEr->find($request->query->get('id'));
            if (!is_null($press->getCompany()))
                $press->getCompany()->setPresses(null);
            return $press;
        }
        if ($request->query->has('ids')) {
            $em = $this->get('doctrine')->getEntityManager();
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            foreach ($pressEr->findByIds($request->query->get('ids')) as $press) {
                if (!is_null($press->getCompany()))
                    $press->getCompany()->setPresses(null);
                $presses[] = $press;
            }
            return $presses;
        }
        if($request->query->has('companyId')) {
            $em = $this->get('doctrine')->getEntityManager();
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            foreach ($pressEr->findByCompany($request->query->get('companyId')) as $press) {
                if (!is_null($press->getCompany()))
                    $press->getCompany()->setPresses(null);
                $presses[] = $press;
            }
            return $presses;
        }
        $condition = null;
        if ($request->query->has('keyword')) {
            $condition['keyword'] = $request->query->get('keyword');
        }
        if ($request->query->has('publishDateStart')) {
            $condition['publishDateStart'] = $request->query->get('publishDateStart');
        }
        if ($request->query->has('publishDateEnd')) {
            $condition['publishDateEnd'] = $request->query->get('publishDateEnd');
        }
        $repositoryManager = $this->container->get('fos_elastica.manager.orm');
        $repository = $repositoryManager->getRepository('SocialWire\Collector\EntityBundle\Entity\Press');
        $pressPager = $repository->search($condition);
        if ($request->query->has('maxPerPage')) {
            $pressPager->setMaxPerPage($request->query->get('maxPerPage'));
        }
        if ($request->query->has('currentPage')) {
            $pressPager->setCurrentPage($request->query->get('currentPage'));
        }
        $presses = array();
        foreach ($pressPager->getCurrentPageResults() as $press) {
            if (!is_null($press->getCompany()))
                $press->getCompany()->setPresses(null);
            $presses[] = $press;
        }

        return array(
            'nbResults' => $pressPager->getNbResults(),
            'nbPages' => $pressPager->getNbPages(),
            'maxPerPage' => $pressPager->getMaxPerPage(),
            'haveToPaginate' => $pressPager->haveToPaginate(),
            'currentPage' => $pressPager->getCurrentPage(),
            'currentPageResults' => $presses
        );
    }

    public function postPressesAction(Request $request)
    {
        try {
            $values = json_decode($request->getContent());
            $em = $this->get('doctrine')->getManager();
            $companyEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.company.class'))->getName());
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            $sourceEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.source.class'))->getName());
            $companyAdded = 0;
            $companyExisted = 0;
            $pressAdded = 0;
            $pressExisted = 0;
            foreach ($values as $value) {
                $existedCompany = $companyEr->findSame($value->company_name);
                if (is_null($existedCompany)) {
                    $company = new Company();
                    $company->setCompanyName($value->company_name);
                    $em->persist($company);
                    $existedPress = null;
                    $companyAdded++;
                } else {
                    $company = $existedCompany;
                    $existedPress = $pressEr->findOneBy(array('pressUrl' => $value->press_url, 'disabled' => false));
                    $companyExisted++;
                }
                if (is_null($existedPress)) {
                    $press = new Press();
                    $source = $sourceEr->findOneBy(array('sourceUrl' => $value->press_source));
                    $press->setSource($source);
                    $press->setPressUrl($value->press_url);
                    $press->setTitle($value->press_title);
                    if (property_exists($value, 'press_subtitle'))
                        $press->setSubTitle($value->press_subtitle);
                    $press->setPublishDate(new \DateTime($value->press_publish_date));
                    $press->setContent($value->press_content);
                    $press->setContentText($value->press_content_text);
                    $press->setCompany($company);
                    $em->persist($press);
                    $buzz = $this->get('buzz');
                    $mediaManager = $this->get('sonata.media.manager.media');
                    if ((property_exists($value, 'images'))) {
                        foreach ($value->images as $image) {
                            if (!empty($image) && @file_get_contents($image->absolute_url) !== false) {
                                $pressImage = new PressImage();
                                $media = $mediaManager->create();
                                $temp = tmpfile();
                                fwrite($temp, $buzz->get($image->absolute_url)->getContent());
                                $meta = stream_get_meta_data($temp);
                                $media->setBinaryContent($meta['uri']);
                                $media->setProviderReference($media->getBinaryContent());
                                $media->setContext('default');
                                $media->setProviderName('sonata.media.provider.image');
                                $mediaManager->save($media);
                                $pressImage->setImage($media);
                                $pressImage->setImageTitle($image->title);
                                $pressImage->setImageSource($image->source);
                                $pool = $this->get('sonata.media.pool');
                                $provider = $pool->getProvider($media->getProviderName());
                                $format = $provider->getFormatName($media, "standard");
                                $pressImage->setImageUrl($request->getScheme() . "://" . $request->getHttpHost() . $provider->generatePublicUrl($media, $format));
                                $pressImage->setPress($press);
                                $press->addPressImage($pressImage);
                                $em->persist($pressImage);
                                $press->setContent(str_replace($image->url, $pressImage->getImageUrl(), $press->getContent()));
                                $em->persist($press);
                            }
                        }
                    }

                    if ((property_exists($value, 'imageFiles'))) {
                        foreach ($value->imageFiles as $imageFile) {
                            if (!empty($imageFile) && @file_get_contents($imageFile->absolute_url) !== false) {
                                $pressImageFile = new PressImageFile();
                                $media = $mediaManager->create();
                                $temp = tmpfile();
                                fwrite($temp, $buzz->get($imageFile->absolute_url)->getContent());
                                $meta = stream_get_meta_data($temp);
                                $media->setBinaryContent($meta['uri']);
                                $media->setProviderReference($media->getBinaryContent());
                                $media->setContext('default');
                                if($imageFile->type=='image'){
                                    $media->setProviderName('sonata.media.provider.image');
                                }else{
                                    $media->setProviderName('sonata.media.provider.file');
                                }
                                $mediaManager->save($media);
                                $pressImageFile->setImageFile($media);
                                $pressImageFile->setImageFileTitle($imageFile->title);
                                $pressImageFile->setImageFileSource($imageFile->source);
                                $pool = $this->get('sonata.media.pool');
                                $provider = $pool->getProvider($media->getProviderName());
                                $format = $provider->getFormatName($media, "reference");
                                $pressImageFile->setImageFileUrl($request->getScheme() . "://" . $request->getHttpHost() . $provider->generatePublicUrl($media, $format));
                                $pressImageFile->setPress($press);
                                $press->addPressImageFile($pressImageFile);
                                $em->persist($pressImageFile);
                                $em->persist($press);
                            }
                        }
                    }

                    if ((property_exists($value, 'files'))) {
                        foreach ($value->files as $file) {
                            if (!empty($file) && @file_get_contents($file->absolute_url) !== false) {
                                $pressFile = new PressFile();
                                $media = $mediaManager->create();
                                $temp = tmpfile();
                                fwrite($temp, $buzz->get($file->absolute_url)->getContent());
                                $meta = stream_get_meta_data($temp);
                                $media->setBinaryContent($meta['uri']);
                                $media->setProviderReference($media->getBinaryContent());
                                $media->setContext('default');
                                $media->setProviderName('sonata.media.provider.file');
                                $mediaManager->save($media);
                                $pressFile->setFile($media);
                                $pressFile->setFileTitle($file->title);
                                $pressFile->setFileSource($file->source);
                                $pool = $this->get('sonata.media.pool');
                                $provider = $pool->getProvider($media->getProviderName());
                                $format = $provider->getFormatName($media, "reference");
                                $pressFile->setFileUrl($request->getScheme() . "://" . $request->getHttpHost() . $provider->generatePublicUrl($media, $format));
                                $pressFile->setPress($press);
                                $press->addPressFile($pressFile);
                                $em->persist($pressFile);
                                $em->persist($press);
                            }
                        }
                    }
                    $company->addPress($press);
                    $pressAdded++;
                } else {
                    $pressExisted++;
                }
                $em->flush();
            }

            $result["status"] = "ok";
            $result["message"] = sizeof($values) . " records. ";
            $result["message"] .= $companyAdded . " companies added. ";
            $result["message"] .= $companyExisted . " companies existed. ";
            $result["message"] .= $pressAdded . " presses added. ";
            $result["message"] .= $pressExisted . " presses existed. ";
        } catch (\Exception $e) {
            $result["status"] = "error";
            $result["message"] = $e->getMessage();
            $result["file"] = $e->getFile();
            $result["line"] = $e->getLine();
            $result["trace"] = $e->getTraceAsString();
        }
        return new Response(json_encode($result));
    }

    /**
     * @Rest\View
     */
    public function updateCompanyAction(Request $request)
    {
        $result = array();
        try {
            $em = $this->get('doctrine')->getManager();
            $companyEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.company.class'))->getName());
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            $companyAdded = 0;
            $sourceAdded = 0;
            foreach ($companyEr->findCompay() as $val) {
                $companyId = $val->getId();
                $pressCount = $pressEr->findCountCompany($companyId);
                if($pressCount<=0){
                    break;
                }
                $firstPublishDate = $pressEr->findPublishDate($companyId, 'ASC');
                $lastPublishDate = $pressEr->findPublishDate($companyId, 'DESC');
                $sources = $pressEr->findSources($companyId);
                $existedEr = $companyEr->findOneBy(array('id' => $companyId));
                $existedEr->setFirstPublishDate($firstPublishDate);
                $existedEr->setLastPublishDate($lastPublishDate);
                $existedEr->setPressCount($pressCount);
                foreach ($sources as $source) {
                    $existedEr->removeSource($source);
                    $existedEr->addSource($source);
                    $sourceAdded++;
                }
                $em->persist($existedEr);
                $em->flush();
                $companyAdded++;
            }
            $result["status"] = "ok";
            $result["message"] = $companyAdded . " companies added. ";
            $result["message"] .= $sourceAdded . " source added. ";
        } catch (\Exception $e) {
            $result["status"] = "error";
            $result["message"] = $e->getMessage();
        }
        return new Response(json_encode($result));
    }

}
