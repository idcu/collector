<?php
namespace Collector\EntityBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdateTimestamps {

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
        if (property_exists($entity, 'updatedAt')) {
            $entity->setUpdatedAt(new \DateTime(date('Y-m-d H:i:s')));
        }
        if (property_exists($entity, 'createdAt')) {
            if($entity->getCreatedAt() == null)
            {
                if (property_exists($entity, 'disabled')){
                    $entity->setDisabled(0);
                }
                $entity->setCreatedAt(new \DateTime(date('Y-m-d H:i:s')));
            }
        }
    }
}