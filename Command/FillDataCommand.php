<?php

namespace Stewie\WikiBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class FillDataCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'stewie:wiki:fill-data';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Fills all data for WikiBundle.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create wiki data')

            // add all or only static groups
            ->addOption('all')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commands = array();
        array_push($commands, 'stewie:wiki:fill-spaces');
        array_push($commands, 'stewie:wiki:fill-pages');
        array_push($commands, 'stewie:wiki:fill-roles');

        if($input->getOption('all')){

            $arguments = [
                '--all'  => true,
            ];

            $outputText = 'All data filled!';

        }else{

            $arguments = [
                '--all'  => false,
            ];

            $outputText = 'Essential data filled!';
        }

        $commandInput = new ArrayInput($arguments);

        // run all commands
        foreach ($commands as &$command) {
            $runCommand = $this->getApplication()->find($command);
            $returnCode = $runCommand->run($commandInput, $output);
        }
        $output->writeln($outputText);

        return 1;
    }
}
