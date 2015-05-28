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

class prtimesCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:prtimes_crawler')
            ->setDescription('get prtimes press data')
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

        $pressSource = 'http://prtimes.jp/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://prtimes.jp/main/html/index/pagenum/".$page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('article>a.link-thumbnail')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });

            $presses = array();
            
            foreach ($pressUrls as $pressUrl) {
                try {
                    $pressUrl = 'http://prtimes.jp'.$pressUrl;
                    $pressUrl = 'http://prtimes.jp/main/html/rd/p/000000027.000006024.html';
                    $response = $browser->get($pressUrl);
                    $content = $response->getContent();
                    $crawler->clear();
                    $crawler->addContent($content);

                    $press['press_source'] = $pressSource;
                    $press['press_url'] = $pressUrl;
                    $output->writeln($press['press_url']);
    //                $output->writeln($crawler->filter('article')->html());
                    $press['press_publish_date'] = date('Y-m-d H:i:s', strtotime($crawler->filter('.header-release-detail>.information-release>time')->attr('datetime')));
                    $output->writeln($press['press_publish_date']);
                    $press['press_title'] = $crawler->filter('.header-release-detail>h2')->text();
                    $output->writeln($press['press_title']);
                    $subtitle = $crawler->filter('.header-release-detail>h3');
                    if ($subtitle->count() > 0)
                        $press['press_subtitle'] = $subtitle->text();
                    $press['company_name'] = trim($crawler->filter('.information-release>.company-name>a')->text(), '');
                    $output->writeln($press['company_name']);


                    $textArray = $crawler->filter('.rbody')->children()->each(function (Crawler $node, $i) {
                        return $node->text();
                    });
                    $press['press_content_text'] = implode('\n', $textArray);

                    $htmlArray = $crawler->filter('.rbody')->children()->each(function (Crawler $node, $i) {
                        return $node->html();
                    });
                    $press['press_content'] = implode('', $htmlArray);
                    //-------imagesfile-----------------------
                    $imageFileArray = $crawler->filter('.box>.file')->children()->each(function (Crawler $node, $i) {
                        return $node->html();
                    });

                    $crawler->clear();
                    $crawler->addContent($press['press_content']);

                    $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                        $image['url'] = $node->attr('src');
                        $urlParts = parse_url($image['url']);
                        $uri = 'http://www.prtimes.jp' . $urlParts['path'];
                        $image['absolute_url'] = $uri;
                        $image['title'] = $node->attr('title');
                        return $image;
                    });
                    $press['images'] = $images;
                    $press['press_content'] = implode('', $htmlArray);
                    
                    $crawler->clear();

                    $crawler->addContent(implode('', $imageFileArray));
                    $imagefile = $crawler->filter('a')->each(function (Crawler $node, $i) {
                        $imagefile['url'] = $node->attr('href');
                        $uri = 'http://www.prtimes.jp' . $imagefile['url'];
                        $imagefile['absolute_url'] = $uri;
                        $imagefile['type'] = 'file';
                        $imagefile['title'] = $node->html();
                        return $imagefile;
                    });
                    $press['imagefile'] = $imagefile;
                    
                    $presses[] = $press;
                }
                catch (\Exception $e) {
                    $output->writeln($e->getMessage());
                    continue;
                }
            }

            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses", array(), json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
