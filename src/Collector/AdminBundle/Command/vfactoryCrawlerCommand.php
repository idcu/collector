<?php
namespace Collector\AdminBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DomCrawler\Crawler;
use Collector\EntityBundle\Entity\PressImage;
use Buzz\Browser;

class vfactoryCrawlerCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this
            ->setName('collector:admin:vfactory_crawler')
            ->setDescription('get vfactory press data')
            ->addArgument(
                'range',
                InputArgument::OPTIONAL,
                'what is press range you wanna get?(all,daily,hourly)'
            )
        ;

    }

    protected function execute(InputInterface $input,OutputInterface $output){
        $range = $input->getArgument('range');
        if (!$range)
        {
            $range = 'hourly';
        }
        $crawlerUrl = 'http://release.vfactory.jp';
        $browser = new Browser();
        $crawler = new Crawler();
        
        $pageNum = 1;
        if ($range == 'hourly')
            $pageNum = 3;
        elseif ($range == 'daily')
            $pageNum = 10;
        elseif ($range == 'all')
            $pageNum = 100;
        for ($page = 1 ;$page<=$pageNum;$page++)
        {
            $pressList = $crawlerUrl . '/' . $page . '.html';
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('table>tr>td>font>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();
            
            foreach($pressUrls as $pressUrl)
            {
                if (!preg_match('/^\/release\/\w+\.html/', $pressUrl)) continue;
                
                $response = $browser->get($crawlerUrl . $pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);
            
                $press['press_source'] = $crawlerUrl;
                $press['press_url'] = $crawlerUrl . $pressUrl;
                //$output->writeln($press['press_url']);
                
                preg_match('/(\d+)?年(\d+)?月(\d+)?日\s(\d+)?時/', $crawler->filter('table>tr>td>.style3')->text(), $datas);
                if( count( $datas ) < 5 ) $data = '';
                $data = $datas[1] . '-' . $datas[2] . '-' . $datas[3] . ' ' . $datas[4] . ':00:00';
                $press['press_publish_date'] = $data;
                //$output->writeln($press['press_publish_date']);
                
                $press['company_name'] = $crawler->filter('table>tr>td>h3')->text();
                //$output->writeln($press['company_name']);

                $press['press_title'] =$crawler->filter('table>tr>td>h2>big>big')->text();
                //$output->writeln($press['press_title']);

                $textArray = $crawler->filter('#contents>tr>td>.style3')->each(function (Crawler $node, $i) {
                    return $node->text();
                });

                $press['press_content_text']=implode('',$textArray);

                $htmlArray = $crawler->filter('#contents>tr>td>.style3')->each(function (Crawler $node, $i) {
                    return $node->html();
                });
                $press['press_content']=implode('',$htmlArray);
                $presses[] = $press;
            }
            
            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses",array(),json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}