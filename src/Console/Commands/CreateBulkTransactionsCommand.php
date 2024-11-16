<?php

namespace Roomvu\Console\Commands;

use AllowDynamicProperties;
use Roomvu\Services\TransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AllowDynamicProperties] class CreateBulkTransactionsCommand extends Command
{
    protected static string $defaultName = 'app:create-bulk-transactions';

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-bulk-transactions')
            ->setDescription('Creates new transactions in bulk.')
            ->setHelp('This command allows you to create a number of transactions at once...')
            ->addArgument('times', InputArgument::REQUIRED, 'Count of new transactions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $times = $input->getArgument('times');
        $this->transactionService->bulkInsert($times);
        $output->writeln("{$times} transactions were created successfully!");

        return Command::SUCCESS;
    }
}