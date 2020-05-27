<?php

namespace Stewie\WikiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;

class WipeDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'stewie:wiki:wipe-data';

    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
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
      $em = $this->container->get('doctrine')->getManager();

      // wipe articles
      $output->writeln('Wiping articles:');
      $repo = $em->getRepository('StewieWikiBundle:Article');
      $articles = $repo->findAll();

      $progressBar = new ProgressBar($output, count($articles));
      $progressBar->start();

      foreach ($articles as &$item){

        $em->remove($item);
        $progressBar->advance();
        }

      $em->flush();
      $progressBar->finish();
      $output->writeln('');

      // end of script
      $output->writeln('All data wiped!');

      return 1;
    }
}
