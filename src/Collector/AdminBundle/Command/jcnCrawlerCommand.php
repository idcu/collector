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

class jcnCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:jcncrawler_crawler')
            ->setDescription('get jcncrawler press data')
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
        $pressSource = 'http://www.jcnnewswire.com';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.jcnnewswire.com/Default.aspx?sid=3&page=" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('td.articleDate>a.newslink')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();

            foreach ($pressUrls as $pressUrl) {
                $pressUrl = 'http://www.jcnnewswire.com/' . $pressUrl;
                //$pressUrl = 'http://www.jcnnewswire.com/Article.aspx?artid=22718&sid=3&headline=%E3%83%88%E3%83%A8%E3%82%BF%E3%80%81%E4%BA%BA%E4%BA%8B%E7%95%B0%E5%8B%95%E3%81%AB%E3%81%A4%E3%81%84%E3%81%A6';
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                //$output->writeln($press['press_url']);
                //2015年5月29日 03時35分

                $date = $crawler->filter('#MainContent_releaseDate')->text();
                $date = str_replace(array('年', '月', '日', '時', '分'), array('-', '-', '', ':', ''), $date);
                $press['press_publish_date'] = $date;
                $press['press_title'] = $crawler->filter('#MainContent_HeadLine')->text();
                //$output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('td.bodytext>strong');
                //if ($subtitle->count() > 0)
                //$press['press_subtitle'] = $subtitle->text();

                $press['company_name'] = $crawler->filter('#MainContent_companySource')->text();
                //$output->writeln($press['company_name']);

                $press['press_content_text'] = $crawler->filter('#MainContent_Body')->text();
                $press['press_content'] = $crawler->filter('#MainContent_Body')->html();
                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://www.atpress.ne.jp' . $urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;
            }
            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses", array(), json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
