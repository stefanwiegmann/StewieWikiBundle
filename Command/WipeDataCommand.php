<?php

namespace Stewie\WikiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Stewie\UserBundle\Service\PathFinder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;

class WipeDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'stewie:wiki:wipe-data';

    private $em;
    private $pathFinder;

    public function __construct(EntityManagerInterface $em, PathFinder $pathFinder)
    {
        parent::__construct();
        $this->em = $em;
        $this->pathFinder = $pathFinder;
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Wipes all data for WikiBundle.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to wipe articles')

            // add all or only static groups
            ->addOption('all')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // wipe spaces
        $output->writeln('Wiping spaces:');
        $repo = $this->em->getRepository('StewieWikiBundle:Space');
        $objects = $repo->findAll();

        $progressBar = new ProgressBar($output, count($objects));
        $progressBar->start();

        foreach ($objects as &$item){

            $this->em->remove($item);
            $progressBar->advance();
        }

        $this->em->flush();
        $progressBar->finish();
        $output->writeln('');

        // wipe articles
        $output->writeln('Wiping articles:');
        $repo = $this->em->getRepository('StewieWikiBundle:Article');
        $objects = $repo->findAll();

        $progressBar = new ProgressBar($output, count($objects));
        $progressBar->start();

        foreach ($objects as &$item){

            $this->em->remove($item);
            $progressBar->advance();
        }

        $this->em->flush();
        $progressBar->finish();
        $output->writeln('');

        // wipe logs
        $output->writeln('Wiping logs:');
        $repo = $this->em->getRepository('StewieWikiBundle:WikiLogEntry');
        $objects = $repo->findAll();

        $progressBar = new ProgressBar($output, count($objects));
        $progressBar->start();

        foreach ($objects as &$item){

            $this->em->remove($item);
            $progressBar->advance();
        }

        $this->em->flush();
        $progressBar->finish();
        $output->writeln('');

      // end of script
      $output->writeln('All data wiped!');

      return 1;
    }
}
