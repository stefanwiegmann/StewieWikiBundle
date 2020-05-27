<?php

namespace Stewie\WikiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Stewie\WikiBundle\Entity\Article;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;

class FillArticleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'stewie:wiki:fill-articles';

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
          ->setDescription('Creates a dummy set of articles.')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp('This command allows you to create articles ...')

          // add all or only static groups
          ->addOption('all')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $em = $this->container->get('doctrine')->getManager();
      $repo = $em->getRepository('StewieWikiBundle:Article');

      $contents = file_get_contents($this->container->get('kernel')->locateResource('@StewieWikiBundle/Resources/data')."/article.json");
      $contents = utf8_encode($contents);
      $results = json_decode($contents, true);

      $i = 0;
      if($input->getOption('all')){
          $i = count($results);
      }else{
          foreach ($results as &$item) {
              if($item['essential']){
                  $i++;
              }
          }
      }

      $progressBar = new ProgressBar($output, $i);
      $output->writeln('Fill articles:');
      $progressBar->start();

      foreach ($results as &$item){

        if($item['essential'] || $input->getOption('all')){

          $article = $repo->findOneByTitle($item['title']);

            if(!$article){
                $article = new Article;
            }else{
                // foreach ($article->getGroupRoles() as &$role){
                //   $article->removeGroupRole($role);
                // }
            }

            $article->setTitle($item['title']);
            $article->setBody($item['body']);

            $em->persist($article);
            $em->flush();

            $progressBar->advance();
          }
        }

        $progressBar->finish();
        $output->writeln('');
        return 1;
      }
}