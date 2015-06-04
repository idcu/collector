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

class mapionPrtimesCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:mapion_prtimes_crawler')
            ->setDescription('get prtimes press data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $browser = new Browser();
        $crawler = new Crawler();

        $pressSource = 'http://prtimes.jp/';
        $pageNum = 2;
        for ($page = 1; $page <= $pageNum; $page++) {
            $pressList = "http://prtimes.jp/main/html/searchrlp/release_type_id/0/company_id/6024/pagenum/" . $page;
            $response = $browser->get($pressList);
            $content = $response->getContent();
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#itemThumbnailView>div.container-thumbnail-list>article>h3>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            $presses = array();
           
            foreach ($pressUrls as $pressUrl) {
                try {
                    $pressUrl = 'http://prtimes.jp' . $pressUrl;
                    $pressUrl = "http://prtimes.jp/main/html/rd/p/000000027.000006024.html";
                    
                    $response = $browser->get($pressUrl);
                    $content = $response->getContent();
                    $crawler->clear();
                    $crawler->addContent($content);

                    $press['press_source'] = $pressSource;
                    $press['press_url'] = $pressUrl;
                    //$output->writeln($press['press_url']);
                    $press['press_publish_date'] = date('Y-m-d H:i:s', strtotime($crawler->filter('.header-release-detail>.information-release>time')->attr('datetime')));
                    //$output->writeln($press['press_publish_date']);
                    $press['press_title'] = $crawler->filter('.header-release-detail>h2')->text();
                    //$output->writeln($press['press_title']);
                    $subtitle = $crawler->filter('.header-release-detail>h3');
                    if ($subtitle->count() > 0)
                        $press['press_subtitle'] = $subtitle->text();
                    try {
                        $press['company_name'] = trim($crawler->filter('.information-release>.company-name>a')->text(), '');
                    } catch (\Exception $e) {
                        $press['company_name'] = trim($crawler->filter('.information-release>.company-name')->text(), '');
                    }
                    //$output->writeln($press['company_name']);


                    $textArray = $crawler->filter('.rbody')->children()->each(function (Crawler $node, $i) {
                        return $node->text();
                    });
                    $press['press_content_text'] = implode('', $textArray);

                    $htmlArray = $crawler->filter('.rbody')->children()->each(function (Crawler $node, $i) {
                        return $node->html();
                    });
                    $press['press_content'] = implode('', $htmlArray);

                    $fileArray = $crawler->filter('.box>.file')->children()->each(function (Crawler $node, $i) {
                        return $node->html();
                    });
                    $imageFileArray = $crawler->filter('ul.slide')->children()->each(function (Crawler $node, $i) {
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
                    $crawler->clear();
                    $crawler->addContent(implode('', $imageFileArray));
                    $imageFiles = $crawler->filter('a')->each(function (Crawler $node, $i) {
                        $imageFile['url'] = $node->attr('href');
                        $uri = 'http://www.prtimes.jp' . $imageFile['url'];
                        $imageFile['absolute_url'] = $uri;
                        $imageFile['title'] = $node->text();
                        return $imageFile;
                    });
                    $press['imageFiles'] = $imageFiles;
                    $crawler->clear();
                    $crawler->addContent(implode('', $fileArray));
                    $files = $crawler->filter('a')->each(function (Crawler $node, $i) {
                        $file['url'] = $node->attr('href');
                        $uri = 'http://www.prtimes.jp' . $file['url'];
                        $file['absolute_url'] = $uri;
                        $file['title'] = $node->text();
                        return $file;
                    });
                    $press['files'] = $files;
                    $presses[] = $press;
                    
                } catch (\Exception $e) {
                    $output->writeln($e->getMessage());
                    continue;
                }
            }

            $buzz = $this->getContainer()->get('buzz');
            $buzz->getClient()->setTimeout(100000);
            $host = $this->getContainer()->getParameter("service_host");
            $result = $buzz->post($host."/rest/presses",array(),json_encode($presses))->getContent();
            $output->writeln($result);
        }
    }

}
