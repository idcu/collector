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

class atpressCrawlerCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this
                ->setName('collector:admin:atpress_crawler')
            ->setDescription('get atpress press data')
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
            $pressList = "http://www.atpress.ne.jp/Default/SearchPr/page_id/".$page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#main>div.pressrelease>div>p>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();

            $pressSource = 'http://www.atpress.ne.jp/';
            foreach($pressUrls as $pressUrl)
            {
                //$pressUrl = 'http://www.atpress.ne.jp/view/62440';
                //$output->writeln($pressUrl);
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                //$output->writeln($press['press_url']);
                $date = date_create_from_format('Y.m.d H:i', $crawler->filter('#pressdetail>.date')->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                //$output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('#pressdetail>.ttl')->text();
                //$output->writeln($press['press_title']);
                $subtitle = $crawler->filter('#pressdetail>.subttl');
                if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['company_name'] = $crawler->filter('#pressdetail>.publisher')->text();
                //$output->writeln($press['company_name']);
                
                $textArray = $crawler->filter('#pressdetail')->children()->each(function (Crawler $node, $i) {
                    if ($node->attr('class')=='txt')
                    {
                        return $node->text();
                    }
                });
                $press['press_content_text']=implode('',$textArray);

                $htmlArray = $crawler->filter('#pressdetail')->children()->each(function (Crawler $node, $i) {
                    if ($node->attr('class')=='txt'||$node->attr('class')=='pic')
                    {
                        return $node->html();
                    }
                });
                $press['press_content']=implode('',$htmlArray);
                $files = $crawler->filter('.pressimg>h3>span>a')->attr('href');
                if(!empty($files))
                    $press['files'] = 'http://www.atpress.ne.jp'.$files;
                $imageFiles = $crawler->filter('.slides>.slide>ul>li>a')->each(function (Crawler $node, $i) {
                    $imageFile['url'] =  $node->attr('href');
                    $uri = 'http://www.atpress.ne.jp'.$imageFile['url'];
                    $imageFile['absolute_url'] = $uri;
                    $imageFile['title'] = $node->attr('title');
                    return $imageFile;
                });
                $press['imageFiles'] = $imageFiles;
                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] =  $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://www.atpress.ne.jp'.$urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;                
            }
            //print_r($presses);exit;
            //$output->writeln($presses);
            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses",array(),json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}