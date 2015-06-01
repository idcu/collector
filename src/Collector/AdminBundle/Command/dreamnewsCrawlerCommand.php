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

class dreamnewsCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:dreamnews_crawler')
            ->setDescription('get dreamnews press data')
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

        $pressSource = 'http://www.dreamnews.jp/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.dreamnews.jp/category/0/" . $page . "/";
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#feed>div.feedItem>.feedhead>.detail>.title>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });

            //$output->writeln($pressUrls);
            $presses = array();
            foreach ($pressUrls as $pressUrl) {
                //$pressUrl = 'http://www.dreamnews.jp/press/0000106557/';
                //$output->writeln($pressUrl);exit;
                
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $date = date_create_from_format('Y年m月d日 H:i', $crawler->filter('#headSec>.date')->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                //$output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('#headSec>h1')->text();
                //$output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('#headSec>h1');
                //if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['press_subtitle'] = '';
                $press['company_name'] = $crawler->filter('#company_name')->text();
                //$output->writeln($press['company_name']);

                $textArray = $crawler->filter('#section01')->children()->each(function (Crawler $node, $i) {
                    if ($node->attr('class') == 'logo' ||
                        ($node->attr('class') == 'block' && $i == 1 ) ||
                        ($node->attr('class') == 'block clearfix')) {
                        return $node->text();
                    }
                });
                $press['press_content_text'] = implode('\n', $textArray);

                $htmlArray = $crawler->filter('#section01')->children()->each(function (Crawler $node, $i) {
                    if ($node->attr('class') == 'logo' ||
                        ($node->attr('class') == 'block' && $i == 1 ) ||
                        ($node->attr('class') == 'block clearfix')) {
                        return $node->html();
                    }
                });
                $press['press_content'] = implode('', $htmlArray);

                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    //$urlParts = parse_url($image['url']);
                    $uri = 'http://www.dreamnews.jp' . $image['url'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;
            }
            //print_r($presses);exit;
            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses", array(), json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
