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
    private $output;

    public function configure()
    {
        $this
            ->setName('assets:install:windows')
            ->setDefinition(array(
                new InputArgument('target', InputArgument::OPTIONAL, 'The target directory', 'web'),
            ))
            ->setDescription('Creates similar assets:install symlinks in Windows with mklink')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command installs bundle assets into a given
directory, under Windows (e.g. the web directory).

<info>php %command.full_name% web</info>

A "bundles" directory will be created inside the target directory, and the
"Resources/public" directory of each bundle will be symlinked into it.

EOT
        )
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $targetArg = rtrim($input->getArgument('target'), '/');

        if (!is_dir($targetArg)) {
            throw new \InvalidArgumentException(sprintf('The target directory "%s" does not exist.', $input->getArgument('target')));
        }

        $filesystem = $this->getContainer()->get('filesystem');

        // Create the bundles directory otherwise symlink will fail.
        $filesystem->mkdir($targetArg.'/bundles/', 0777);

        $output->writeln("Installing assets using the mklink cmd");

        foreach ($this->getContainer()->get('kernel')->getBundles() as $bundle) {
            if (is_dir($originDir = $bundle->getPath().'/Resources/public')) {
                $bundlesDir = $targetArg.'/bundles/';
                $targetDir  = $bundlesDir.preg_replace('/bundle$/', '', strtolower($bundle->getName()));

                $output->writeln(sprintf('Installing assets for <comment>%s</comment> into <comment>%s</comment>', $bundle->getNamespace(), $targetDir));

                $filesystem->remove($targetDir);

                $relativeOriginDir = $originDir;

                $this->mklink($relativeOriginDir, $targetDir);
            }
        }
    }

    protected function mklink($origin, $target)
    {
        return $this->process(sprintf("mklink /D %s %s", $origin, $target));
    }

    private function process($command)
    {
        $process = new \Symfony\Component\Process\Process($command);
        $return = $process->run();
        if($process->isSuccessful()) {
            $this->output->writeln($process->getOutput());
        }
        else {
            $this->output->writeln($process->getErrorOutput());
        }
        return $return;
    }
}