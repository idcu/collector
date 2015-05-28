<?php
namespace Collector\AdminBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class pressUpdateCommand extends ContainerAwareCommand{

    protected function configure()
    {
        $this
            ->setName('collector:press:update')
            ->setDescription('Update press')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $pressEr = $em->getRepository($em->getClassMetadata($this->getContainer()->getParameter('collector.press.class'))->getName());
        $presses = $pressEr->findAtpressPresses();
        foreach ($presses as $press){
            $press->setDisabled(true);
            $em->persist($press);
        }
        $em->flush();
    }
}