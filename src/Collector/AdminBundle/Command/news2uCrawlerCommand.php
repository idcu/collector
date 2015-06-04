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

class news2uCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:news2u_crawler')
            ->setDescription('get news2u press data')
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

        $pressSource = 'http://www.news2u.net/';
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://www.news2u.net/corporates/releases/CR20081202/page:" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#main_column>ul.release_list>li>div>h3>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            //$output->writeln($pressUrls);
            $presses = array();
            foreach ($pressUrls as $pressUrl) {
                //$pressUrl = 'http://www.news2u.net/releases/132412';
                $pressUrl = 'http://www.news2u.net' . $pressUrl;
                //$output->writeln($pressUrl);
                $response = $browser->get($pressUrl);
                $content = $response->getContent();
                $crawler->addContent($content);

                $press['press_source'] = $pressSource;
                $press['press_url'] = $pressUrl;
                $date = date_create_from_format('Y年m月d日 H時i分', $crawler->filter('.release_information>.release_date')->text());
                $press['press_publish_date'] = date_format($date, 'Y-m-d H:i:s');
                //$output->writeln($press['press_publish_date']);
                $press['press_title'] = $crawler->filter('.release_information>h1')->text();
                //$output->writeln($press['press_title']);
                //$subtitle = $crawler->filter('#pressdetail>.subttl');
                //if ($subtitle->count()>0) $press['press_subtitle'] = $subtitle->text();
                $press['press_subtitle'] = '';
                $press['company_name'] = $crawler->filter('.corporate_name')->text();
                //$output->writeln($press['company_name']);

                $textArray = $crawler->filter('.release_body')->children()->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                $press['press_content_text'] = implode('\n', $textArray);

                $htmlArray = $crawler->filter('.release_body')->children()->each(function (Crawler $node, $i) {
                    return $node->html();
                });
                $press['press_content'] = implode('', $htmlArray);

                $imageFiles = $crawler->filter('.release_attachment_inner>ul>li>a>img')->each(function (Crawler $node, $i) {
                    $imageFile['url'] = $node->attr('src');
                    $uri = $imageFile['url'];
                    $imageFile['absolute_url'] = $uri;
                    $imageFile['title'] = $node->attr('title');
                    return $imageFile;
                });
                $press['imageFiles'] = $imageFiles;

                $crawler->clear();
                $crawler->addContent($press['press_content']);
                $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                    $image['url'] = $node->attr('src');
                    $urlParts = parse_url($image['url']);
                    $uri = 'http://itm.news2u.net' . $urlParts['path'];
                    $image['absolute_url'] = $uri;
                    $image['title'] = $node->attr('title');
                    return $image;
                });
                $press['images'] = $images;
                $presses[] = $press;
            }

            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $host = $this->getContainer()->getParameter("service_host");
            $result = $buzz->post($host."/rest/presses",array(),json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
