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

class zaikeiCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:zaikei_crawler')
            ->setDescription('get zaikei press data')
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

        $browser = new Browser();
        $crawler = new Crawler();

        $pageNum = 1;
        if ($range == 'hourly')
            $pageNum = 3;
        elseif ($range == 'daily')
            $pageNum = 10;
        elseif ($range == 'all')
            $pageNum = 100;
        $pressSource = 'http://www.zaikei.co.jp/press/provider/26/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.zaikei.co.jp/press/provider/26/p" . $page . ".html";
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('.news_list>div.article_02>p.link>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();

            foreach ($pressUrls as $pressUrl) {
                $pressUrl = 'http://www.zaikei.co.jp' . $pressUrl;
                //$pressUrl = 'http://www.zaikei.co.jp/releases/229116/';
                 
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                //$output->writeln($press['press_url']);
                $date = date_create_from_format('Y-m-d H:i:s', $crawler->filter('.pankuzu>.fr')->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                //$output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('#kiji_title>.tit')->text();
                //$output->writeln($press['press_title']);
                $press['press_subtitle'] = '';
                $press['company_name'] = $crawler->filter('.div_press_article_company_name>a')->text();
                //$output->writeln($press['company_name']);

                $press['press_content_text'] = $crawler->filter('.description')->text();
                $press['press_content'] = $crawler->filter('.description')->html();

                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://www.zaikei.co.jp' . $urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;
                //print_r($presses);exit;
            }
            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses", array(), json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
