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
        $pressSource = 'http://www.japancorp.net/japan/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.japancorp.net/japan/all_news.asp?iPage=1&Page_Num=" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('p>a.newslink')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            $output->writeln($pressUrls);
            $presses = array();

            foreach ($pressUrls as $pressUrl) {
                $pressUrl = 'http://www.japancorp.net' . $pressUrl;
                //$pressUrl = 'http://www.japancorp.net/japan/Article.Asp?Art_ID=64176';
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $output->writeln($press['press_url']);
                $companys = $crawler->filter('td')->each(function (Crawler $node, $i) {
                    if ($node->attr('style') == 'font-size:16px;font-family:ＭＳ Ｐゴシック,Osaka,ＭＳ ゴシック,ヒラギノ角ゴ, sans-serif,verdana;line-height:20px;color:#990000;font-weight:bold;') {
                        return $node->text();
                    }
                });

                preg_match("/([0-9]*)年([0-9]*)月([0-9]*)日 ([0-9]*)時([0-59]*)分/", $crawler->filter('#release_body')->text(), $data);
                $date = date_create_from_format('Y年月d日 H時i分', $data[0]);
                if (!empty($date)) {
                    $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                    $output->writeln($press['press_publish_date']);
                }
                $press['press_title'] = $crawler->filter('td.header')->text();
                $output->writeln($press['press_title']);
                $subtitle = $crawler->filter('td.bodytext>strong');
                if ($subtitle->count() > 0)
                    $press['press_subtitle'] = $subtitle->text();
                if (!empty($companys[24]))
                    $press['company_name'] = $companys[24];
                $output->writeln($press['company_name']);

                $press['press_content_text'] = $crawler->filter('#release_body')->text();
                $press['press_content'] = $crawler->filter('#release_body')->html();

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
