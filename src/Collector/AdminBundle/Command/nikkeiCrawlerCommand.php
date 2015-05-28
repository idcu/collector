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

class nikkeiCrawlerCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this
            ->setName('collector:admin:nikkei_crawler')
            ->setDescription('get nikkei press data')
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
        $presses = array();
        
        $browser = new Browser();
        $crawler = new Crawler();
        $pageNum = 1;
        if ($range == 'hourly')
            $pageNum = 3;
        elseif ($range == 'daily')
            $pageNum = 10;
        elseif ($range == 'all')
            $pageNum = 100;
        
        $pressSource = 'http://release.nikkei.co.jp/';
        for ($page = 1 ;$page<=$pageNum;$page++)
        {
            $pressList = "http://release.nikkei.co.jp/index.cfm?page=".$page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#release>ul.ul_sq>li>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            $output->writeln($pressUrls);
            $presses = array();
            foreach($pressUrls as $pressUrl)
            {
                //$pressUrl = 'http://release.nikkei.co.jp/detail.cfm?relID=379661&lindID=5';
                $pressUrl = $pressSource.'/'.$pressUrl;
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $date = date_create_from_format('Y/m/d', $crawler->filter('#re_table>tr>td')->eq(1)->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d');

                $output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('#heading')->text();
                $output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('#pressdetail>.subttl');
                //if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['press_subtitle'] = '';
                $company_name = $crawler->filter('#re_table>tr>td')->eq(2)->text();
                $company_name = explode('|', $company_name);
                $press['company_name'] = str_replace(' ','',trim($company_name[0]));
                $output->writeln($press['company_name']);
                $textArray = $crawler->filter('div#contents>p')->eq(1)->text();

                $press['press_content_text']=$textArray;
                $htmlArray = $crawler->filter('div#contents>p')->eq(1)->html();
                $press['press_content']=$htmlArray;

                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] =  $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://release.nikkei.co.jp'.$urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;


                $buzz = $this->getContainer()->get('buzz');
                $buzz->getClient()->setTimeout(100000);
                $result = $buzz->post("http://collector.cointelligence.cn/rest/presses",array(),json_encode($presses))->getContent();
                $output->writeln($result);
            }
        }
    }

}