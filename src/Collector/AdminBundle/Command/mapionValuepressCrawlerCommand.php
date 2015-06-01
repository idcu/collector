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

class mapionValuepressCrawlerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:admin:mapion_valuepress_crawler')
            ->setDescription('get valuepress press data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $browser = new Browser();
        $crawler = new Crawler();

        $pressSource = 'https://www.value-press.com/';
        $pageNum = 8;

        for ($page = 0; $page <= $pageNum; $page++) {
            if ($page == 0) {
                $pressList = "https://www.value-press.com/corporation/release/8010";
            } else {
                $pressList = "https://www.value-press.com/corporation/release/8010?&per_page=" . $page * 10;
            }
           
            $response = $browser->get($pressList);
            $content = $response->getContent();
            print_r($content);exit;
            $crawler->addContent($content);
            $pressUrls = $crawler->filter('#contents_main>div.pressrelease_article>h3>a')->each(function (Crawler $node, $i) {
                return $node->attr('href');
            });
            print_r($pressUrls); exit;
            $presses = array();
            foreach ($pressUrls as $pressUrl) {
                try {
                    $pressUrl = $pressUrl;
//                    $pressUrl = "http://www.value-press.com/pressrelease/138424";

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

                    $press['press_content_text'] = $crawler->filter('#contents_main>p.line02')->text() . $crawler->filter('#contents_main>div.pressrelease_content')->text();
                    $press['press_content'] = $crawler->filter('#contents_main>p.line02')->html() . $crawler->filter('#contents_main>div.release_icatch_imagebox')->html() . $crawler->filter('#contents_main>div.pressrelease_content')->html();

                    $imageFileNode = $crawler->filter('#contents_main>.photo_list01');
                    if ($imageFileNode->count() > 0)
                        $imageFileArray = $imageFileNode->children()->each(function (Crawler $node, $i) {
                            return $node->html();
                        });
                    $fileNode = $crawler->filter('#contents_main>ul.box01');
                    if ($fileNode->count() > 0)
                        $fileArray = $fileNode->children()->each(function (Crawler $node, $i) {
                            return $node->html();
                        });

                    $crawler->clear();
                    $crawler->addContent($press['press_content']);
                    $images = $crawler->filter('img')->each(function (Crawler $node, $i) {
                        $image['url'] = $node->attr('src');
                        $image['absolute_url'] = $image['url'];
                        $image['title'] = $node->attr('title');
                        return $image;
                    });
                    $press['images'] = $images;

                    if (isset($imageFileArray)) {
                        $crawler->clear();
                        $crawler->addContent(implode('', $imageFileArray));
                        $imageFiles = $crawler->filter('a')->each(function (Crawler $node, $i) {
                            $imageFile['url'] = $node->attr('href');
                            $uri = $imageFile['url'];
                            $imageFile['absolute_url'] = $uri;
                            $imageFile['title'] = $node->text();
                            return $imageFile;
                        });
                        $press['imageFiles'] = $imageFiles;
                    }
                    if (isset($fileArray)) {
                        $crawler->clear();
                        $crawler->addContent(implode('', $fileArray));
                        $files = $crawler->filter('a')->each(function (Crawler $node, $i) {
                            $file['url'] = $node->attr('href');
                            $uri = $file['url'];
                            $file['absolute_url'] = $uri;
                            $file['title'] = $node->text();
                            return $file;
                        });
                        $press['files'] = $files;
                    }
                    $presses[] = $press;
                    print_r($presses);
                    exit;
                } catch (\Exception $e) {
                    $output->writeln($e->getMessage());
                    continue;
                }
            }
//            $buzz = $this->getContainer()->get('buzz');
//            $buzz->getClient()->setTimeout(100000);
//            $result = $buzz->post("http://collector.cointelligence.cn/rest/presses", array(), json_encode($presses))->getContent();
//            $output->writeln($result);
        }
    }

}
