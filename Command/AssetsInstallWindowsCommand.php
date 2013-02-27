<?php

namespace TSS\AssetsInstallWindowsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpFoundation\Request;

class AssetsInstallWindowsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('assets:install:windows')
            ->setDescription('Creates similar assets:install symlinks in Windows with mklink');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {            
        $output->write('xx');
        
    }
}