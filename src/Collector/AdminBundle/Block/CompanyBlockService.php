<?php

namespace Collector\AdminBundle\Block;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompanyBlockService extends BaseBlockService
{

    private $em;

    public function entityManager($entityManager)
    {
        $this->em = $entityManager;
    }

    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url' => false,
            'title' => 'マニュアル',
            'template' => 'CollectorAdminBundle:Block:company.html.twig',
        ));
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $data = $this->getContact();
        $params = array(
            'context' => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block' => $blockContext->getBlock(),
            'data' => $data
        );
        return $this->renderResponse($blockContext->getTemplate(), $params, $response);
    }

    public function getContact()
    {
        $param = array(
            'myCreditStatus' => 2
        );
        $data = array();
        try {
            $data = $this->em->getRepository('Collector\EntityBundle\Entity\Company')->findBy($param,array('id'=>'DESC'));

        } catch (\Exception $e) {
            
        }
        return $data;
    }

}
