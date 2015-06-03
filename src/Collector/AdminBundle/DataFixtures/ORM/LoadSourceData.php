<?php
namespace Collector\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Collector\EntityBundle\Entity\Source;

class LoadSourceData implements FixtureInterface{

    public function load(ObjectManager $manager)
    {
        $sourceImport = array(
            array("＠Press","http://www.atpress.ne.jp/",100),
            array("PR Times","http://prtimes.jp/",0),
            array("Value Press","http://www.value-press.com/",0),
            array("VFリリース","http://release.vfactory.jp/",0),
            array("DreamNews","http://www.dreamnews.jp/",0),
            array("日経プレスリリース","http://release.nikkei.co.jp/",0),
            array("共同通信PRワイヤー（国内)","http://prw.kyodonews.jp/opn/news-release/",0),
            array("共同通信PRワイヤー（海外から)","http://prw.kyodonews.jp/opn/category/release/kaigaikara/page/1/",0),
            array("共同通信PRワイヤー（海外向け）","http://prw.kyodonews.jp/opn/category/release/kaigaimuke/page/1/",0),
            array("共同通信PRワイヤー（駐日特派員向け）","http://prw.kyodonews.jp/opn/category/release/tokuhain/page/1/",0),
            array("JCN Network","http://www.japancorp.net/japan/",0),
            array("財経新聞 (ValuePress)","http://www.zaikei.co.jp/press/provider/26/",0),
            array("Digital PR Platform","http://digitalpr.jp/",0),
        );

        foreach ($sourceImport as $data)
        {
            $source = new Source();
            $i=0;
            $source->setSourceName($data[$i++]);
            $source->setSourceUrl($data[$i++]);
            $source->setSourceRank($data[$i++]);
            $manager->persist($source);
        }
        $manager->flush();
    }

}