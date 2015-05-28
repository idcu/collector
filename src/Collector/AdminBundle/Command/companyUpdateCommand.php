<?php

namespace Collector\AdminBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Finder\Finder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class companyUpdateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('collector:company:update')
            ->setDescription('Update company')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $presses = array();
        $buzz = $this->getContainer()->get('buzz');
        //$url = $this->getContainer()->get('router')->generate('post_update_company', array(), true);
        $url = 'http://collector.cointelligence.cn/rest/companies/update';
        $result = $buzz->get($url, array(), json_encode($presses))->getContent();
        $output->writeln($result);
    }

}
