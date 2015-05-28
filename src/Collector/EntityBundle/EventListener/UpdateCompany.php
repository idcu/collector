<?php
namespace Collector\EntityBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Collector\EntityBundle\Entity\Company;
use Collector\EntityBundle\Entity\Press;

class UpdateCompany {

    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->index($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        $this->index($args);
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof Press){
            $em = $args->getEntityManager();
            $pressEr = $em->getRepository($em->getClassMetadata($this->container->getParameter('collector.press.class'))->getName());
            $company = $entity->getCompany();
            $companyId = $company->getId();
            $pressCount = $pressEr->findCountCompany($companyId)+1;
            $firstPublishDate = $pressEr->findPublishDate($companyId, 'ASC');
            $lastPublishDate = $pressEr->findPublishDate($companyId, 'DESC');
            $company->setFirstPublishDate($firstPublishDate);
            $company->setLastPublishDate($lastPublishDate);
            $company->setPressCount($pressCount);
            $sources = $pressEr->findSources($companyId);
            foreach ($sources as $source) {
                $company->removeSource($source);
                $company->addSource($source);
            }
        }
    }
} 