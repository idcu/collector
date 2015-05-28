<?php
namespace Common\AdminBundle\Model;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseModelManager;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Exception\ModelManagerException;

class ModelManager extends BaseModelManager{

    public function delete($object)
    {
        try {
            $entityManager = $this->getEntityManager($object);
            if (property_exists ($object , 'disabled' ))
            {
                $object->setDisabled(true);
                $entityManager->persist($object);
                $entityManager->flush();
            }
            else{
                $entityManager->remove($object);
                $entityManager->flush();
            }
        } catch (\PDOException $e) {
            throw new ModelManagerException(sprintf('Failed to delete object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
        } catch (DBALException $e) {
            throw new ModelManagerException(sprintf('Failed to delete object: %s', ClassUtils::getClass($object)), $e->getCode(), $e);
        }
    }

    public function batchDelete($class, ProxyQueryInterface $queryProxy)
    {
        $queryProxy->select('DISTINCT '.$queryProxy->getRootAlias());

        try {
            $entityManager = $this->getEntityManager($class);

            $i = 0;
            foreach ($queryProxy->getQuery()->iterate() as $pos => $object) {
                if (property_exists ($object[0] , 'disabled' ))
                {
                    $object[0]->setDisabled(true);
                    $entityManager->persist($object[0]);
                }
                else{
                    $entityManager->remove($object[0]);
                }
                if ((++$i % 20) == 0) {
                    $entityManager->flush();
                    $entityManager->clear();
                }
            }

            $entityManager->flush();
            $entityManager->clear();
        } catch (\PDOException $e) {
            throw new ModelManagerException('', 0, $e);
        } catch (DBALException $e) {
            throw new ModelManagerException('', 0, $e);
        }
    }
} 