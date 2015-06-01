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

class digitalprCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:digitalpr_crawler')
            ->setDescription('get digitalpr press data')
            ->addArgument(
                'range', InputArgument::OPTIONAL, 'what is press range you wanna get?(all,daily,hourly)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $range = $input->getArgument('range');
        if (!$range) {
            $range = '';
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
        $pressSource = 'http://digitalpr.jp/';
        for ($page = $pageNum; $page <= $pageNum; $page--) {
            $pressList = "http://digitalpr.jp/new/" . (date('Ymd') - $page);

            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('.list>div.listBox>div.listBoxR>p.camLink>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();
            if ($pressUrls) {
                foreach ($pressUrls as $pressUrl) {
                    $pressUrl = 'http://digitalpr.jp' . $pressUrl;
                    //$pressUrl = 'http://digitalpr.jp/r/11778';
                    $response = $browser->get($pressUrl);
                    $content = $response->getContent();
                    $crawler->clear();
                    $crawler->addContent($content);

                    $press['press_source'] = $pressSource;
                    $press['press_url'] = $pressUrl;
                    //$output->writeln($press['press_url']);
                    $date = date_create_from_format('Y年m月d日H時i分', $crawler->filter('div.detailTitL>p.day')->text());
                    $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                    //$output->writeln($press['press_publish_date']);
                    $press['press_title'] = $crawler->filter('.detailCon>h2')->text();

                    //$output->writeln($press['press_title']);
                    $subtitle = $crawler->filter('.detailCon>.subTit');
                    if ($subtitle->count() > 0)
                        $press['press_subtitle'] = $subtitle->text();
                    $press['company_name'] = $crawler->filter('.detailTitL>.camTit')->text();
                    //$output->writeln($press['company_name']);


                    $press['press_content_text'] = $crawler->filter('.detailCon>p')->eq(1)->text();
                    $press['press_content'] = $crawler->filter('.detailCon>p')->eq(1)->html();

                    $crawler->clear();
                    $crawler->addContent($press['press_content']);
                    $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                        $image['url'] = $node->attr('src');
                        $urlParts = parse_url($image['url']);
                        $uri = 'http://digitalpr.jp' . $urlParts['path'];
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

}
