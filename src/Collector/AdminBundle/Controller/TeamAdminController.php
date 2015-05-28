<?php
namespace Collector\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Collector\AdminBundle\Command\SearchAndSaveCommand;
use Sonata\AdminBundle\Controller\CRUDController as BaseController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Zend\Json\Expr;

class TeamAdminController extends BaseController{

    public function chartAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('VIEW', $object)) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $series = array(
            array("name" => "月間残り","data" => array(289,315,321,2593,773,0,0)),
            array("name" => "月間累積","data" => array(51,56,57,458,136,0,0)),
        );

        $categories = array('障害', '作業依頼', '定常作業', '問合せ', '案件対応', '追加無償', '夜間休日有償');

        $ob = new Highchart();
        $ob->chart->renderTo('chart');  // The #id of the div where to render the chart
        $ob->chart->type('column');
        $ob->title->text('チーム別チケット状況');
        $ob->xAxis->title(array('text'  => "作業種類"));
        $ob->xAxis->categories($categories);
        $ob->yAxis->min(0);
        $ob->yAxis->title(array('text'  => "チケット数"));
        $ob->yAxis->stackLabels(array('enabled'  => true,'style' => array('fontWeight'=>'bold')));
        $formatter = new Expr('function () {
                return "<b>" + this.x + "</b><br/>" +
                    this.series.name + ": " + this.y + "<br/>" +
                    "月間合計: " + this.point.stackTotal;
            }');
        $ob->tooltip->formatter($formatter);
        $ob->plotOptions->column(array(
            'stacking'  => 'normal',
            'dataLabels' => array('enabled' => false,'style'=>array('textShadow'=>'0 0 3px black')),
        ));
        $ob->legend->align('right');
        $ob->legend->x(-30);
        $ob->legend->verticalAlign('top');
        $ob->legend->y(25);
        $ob->legend->floating(true);
        $ob->legend->borderColor('#CCC');
        $ob->legend->borderWidth(1);
        $ob->legend->shadow(false);

        $ob->series($series);

        return $this->render($this->admin->getTemplate('chart'), array(
            'action'   => 'chart',
            'object'   => $object,
            'chart' => $ob,
            'elements' => $this->admin->getShow(),
        ));
    }
} 