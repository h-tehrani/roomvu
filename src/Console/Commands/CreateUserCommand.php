<?php

namespace Roomvu\Console\Commands;

use AllowDynamicProperties;
use Roomvu\Services\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AllowDynamicProperties] class CreateUserCommand extends Command
{
    protected static string $defaultName = 'app:create-user';

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the new user')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the new user')
            ->addArgument('credit', InputArgument::REQUIRED, 'Credit of the new user')
            ->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data['email'] = $input->getArgument('email');
        $data['name'] = $input->getArgument('name');
        $data['credit'] = $input->getArgument('credit');
        $user = $this->userService->createUser($data);
        $output->writeln("User {$user->getId()} was created successfully!");

        return Command::SUCCESS;
    }
}