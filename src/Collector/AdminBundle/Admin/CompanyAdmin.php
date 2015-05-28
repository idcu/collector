<?php
namespace Collector\AdminBundle\Admin;

use Common\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Translator\FormLabelTranslatorStrategy;
use Sonata\AdminBundle\Admin\AdminInterface;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class CompanyAdmin extends Admin
{
    protected $baseRoutePattern = 'company';
    protected $selection;
    public function setSelection(Selection $selection){
        $this->selection = $selection;
    }

    public function initialize()
    {
        parent::initialize();
        $this->setUniqid('CompanyAdmin');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('companyName',null,array('required' => true))
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('companyName')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('companyName')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('companyName')
        ;
    }


    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit','show'))) {
            return;
        }

//        $admin = $this->isChild() ? $this->getParent() : $this;
        $admin = $this;
        $id = $admin->getRequest()->get('id');
//        $menu->addChild(
//            '編集',
//            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
//        );
//        $menu->addChild(
//            '表示',
//            array('uri' => $admin->generateUrl('show', array('id' => $id)))
//        );
//        $menu->addChild('プレスリリース一覧', array('uri' => $childAdmin->generateUrl('list')));
        if (!is_null($childAdmin) && $childAdmin->getCode() == "collector.press.admin")
        {
            $menu->addChild('プレスリリース一覧', array('uri' => $childAdmin->generateUrl('list')));
        }
        else
        {
            $menu->addChild('プレスリリース一覧', array('uri' => $admin->generateUrl('collector.press.admin.list', array('id' => $id))));
        }
    }
}