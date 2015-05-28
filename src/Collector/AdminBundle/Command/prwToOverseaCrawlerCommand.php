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

class prwToOverseaCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:prwtooversea_crawler')
            ->setDescription('get prwtooversea press data')
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
        $pressSource = 'http://prw.kyodonews.jp/opn/category/release/kaigaimuke/page/1/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://prw.kyodonews.jp/opn/category/release/kaigaimuke/page/" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#contMainBox>div.h5-section>div.h5-article>div.articleMain>div.text>h2>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            $output->writeln($pressUrls);
            $presses = array();

            foreach ($pressUrls as $pressUrl) {
                //$pressUrl = 'http://prw.kyodonews.jp/opn/release/201502107571/';
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->clear();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $output->writeln($press['press_url']);
                //$date = date_create_from_format('Y年m月d日', $crawler->filter('.releaseInfo>.date')->text());
                $press['press_publish_date'] = date('Y-m-d H:i:s',strtotime($crawler->filter('.releaseInfo>.date')->text()));
                $output->writeln($press['press_publish_date']);
                $press['press_title'] = trim($crawler->filter('.releaseInfo>h1')->text());
                $output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('#pressdetail>.subttl');
                //if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['press_subtitle'] = '';
                $press['company_name'] = $crawler->filter('.releaseInfo>.fromMember')->text();
                $output->writeln($press['company_name']);

                $press['press_content_text'] = $crawler->filter('.releaseText>.rel_honbun')->text();
                $press['press_content'] = $crawler->filter('.releaseText>.rel_honbun')->html();

                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://prw.kyodonews.jp' . $urlParts['path'];
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
