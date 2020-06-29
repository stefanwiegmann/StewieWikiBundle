<?php

namespace Stewie\WikiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Stewie\UserBundle\Entity\Role;
// use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Stewie\UserBundle\Service\AvatarGenerator;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityManagerInterface;
use Stewie\WikiBundle\Service\PathFinder;
use Stewie\UserBundle\Service\RoleUpdater;

class FillRolesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'stewie:wiki:fill-roles';

    private $em;
    private $pathFinder;
    private $avatarGenerator;
    private $roleUpdater;

    public function __construct(EntityManagerInterface $em, PathFinder $pathFinder, AvatarGenerator $avatarGenerator, RoleUpdater $roleUpdater)
    {
        parent::__construct();
        $this->em = $em;
        $this->pathFinder = $pathFinder;
        $this->avatarGenerator = $avatarGenerator;
        $this->roleUpdater = $roleUpdater;
    }

    protected function configure()
    {
      $this
          // the short description shown while running "php bin/console list"
          ->setDescription('Creates roles for wiki bundle.')

          // the full command description shown when running the command with
          // the "--help" option
          ->setHelp('This command allows you to create wiki bundle roles...')

          // add all or only static groups
          ->addOption('all')
      ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // $em = $this->container->get('doctrine')->getManager();
        $repo = $this->em->getRepository('StewieUserBundle:Role');

        $contents = file_get_contents($this->pathFinder->getBundlePath().'Resources/data/roles.json');
        $contents = utf8_encode($contents);
        $results = json_decode($contents, true);

        $progressBar = new ProgressBar($output, count($results));
        $output->writeln('Fill roles:');
        $progressBar->start();

        foreach ($results as &$item){

            $role = $repo->findOneByName($item['name']);

            if(!$role){
                $role = new Role;
            }

            $role->setName($item['name']);
            $role->setSort($item['sort']);
            $role->setDescription($item['description']);
            $role->setAvatarName($this->avatarGenerator->create($role));

            $this->em->persist($role);
            $this->em->flush();

            $progressBar->advance();
        }

        $this->roleUpdater->assignAdmins();

        $progressBar->finish();
        $output->writeln('');
        return 1;
    }
}
