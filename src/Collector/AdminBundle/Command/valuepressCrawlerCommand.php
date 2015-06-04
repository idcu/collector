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

class valuepressCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:valuepress_crawler')
            ->setDescription('get valuepress press data')
            ->addArgument(
                'range', InputArgument::OPTIONAL, 'what is press range you wanna get?(all,daily,hourly)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $range = $input->getArgument('range');
        if (!$range) {
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

        $pressSource = 'http://www.value-press.com/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.value-press.com/top/get_more_release?page=" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            //$crawler->addContent($content);
            $pressUrls = json_decode($content,true);
           
            //$output->writeln($pressUrls);
            $presses = array();
            foreach ($pressUrls['data']['list'] as $pressUrl) {
//                $pressUrl = 'http://www.value-press.com/pressrelease/'.$pressUrl['article_id'];
                $pressUrl = "http://www.value-press.com/pressrelease/138424";
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $date = date_create_from_format('Y年m月d日 H時', $crawler->filter('#press_datetime')->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                //$output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('#press_title')->text();
                //$output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('#pressdetail>.subttl');
                //if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['press_subtitle'] = '';
                $press['company_name'] = $crawler->filter('.press_company_name')->text();

                //$output->writeln($press['company_name']);
                $press['press_content_text'] = $crawler->filter('#contents_main>p.line02')->text().$crawler->filter('#contents_main>div.pressrelease_content')->text();
                $press['press_content'] = $crawler->filter('#contents_main>p.line02')->html().$crawler->filter('#contents_main>div.release_icatch_imagebox')->html().$crawler->filter('#contents_main>div.pressrelease_content')->html();
                $imageFileArray = $crawler->filter('#contents_main>.photo_list01')->html();


                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = "https://files.value-press.com".$urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                
                //----imagefile----------------
                $crawler->addContent($imageFileArray);
                $imagefile = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $imagefile['url'] = $node->attr('src');
                    $urlParts = parse_url($imagefile['url']);
                    $uri = "https://files.value-press.com".$urlParts['path'];
                    $imagefile['absolute_url'] = $uri;
                    $imagefile['title'] = $node->attr('alt');
                    return $imagefile;
                });
                $press['imagefile'] = $imagefile;
                
                
                $presses[] = $press;
                print_r($presses);exit;
            }

            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $host = $this->getContainer()->getParameter("service_host");
            $result = $buzz->post($host."/rest/presses",array(),json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
