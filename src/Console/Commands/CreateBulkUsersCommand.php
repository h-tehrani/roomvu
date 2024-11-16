<?php

namespace Roomvu\Console\Commands;

use AllowDynamicProperties;
use Roomvu\Services\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AllowDynamicProperties] class CreateBulkUsersCommand extends Command
{
    protected static string $defaultName = 'app:create-bulk-users';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-bulk-users')
            ->setDescription('Creates new users in bulk.')
            ->setHelp('This command allows you to create a number of users at once...')
            ->addArgument('times', InputArgument::REQUIRED, 'Count of new users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $times = $input->getArgument('times');
        $this->userService->bulkInsert($times);
        $output->writeln("{$times} users were created successfully!");

        return Command::SUCCESS;
    }
}