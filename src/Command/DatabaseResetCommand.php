<?php

namespace App\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseResetCommand extends Command
{
    protected static $defaultName = 'app:database:reset';
    protected static $defaultDescription = 'This command resets the database';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Application */
        $application = $this->getApplication();

        $force = new ArrayInput(['--force' => true]);
        $noInteraction = new ArrayInput([]);
        $noInteraction->setInteractive(false);

        $application->find('doctrine:database:drop')->run($force, $output);
        $application->find('doctrine:database:create')->run($input, $output);
        $application->find('doctrine:migrations:migrate')->run($noInteraction, $output);
        $application->find('doctrine:fixtures:load')->run($noInteraction, $output);

        return Command::SUCCESS;
    }
}
