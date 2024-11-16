<?php

namespace Roomvu\Console\Commands;

use AllowDynamicProperties;
use Roomvu\Services\TransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AllowDynamicProperties] class GetUserDailyTransactionsCommand extends Command
{
    protected static string $defaultName = 'app:get-user-daily-transactions';

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:get-user-daily-transactions')
            ->setDescription('Generates a report of total transaction amounts by a specific user for a given day')
            ->setHelp("This command allows you to receive a total transactions sum for a specific user for a given day. 
                       You must provide the user ID and the date (in the format 'yyyy-mm-dd') when calling this command , 
                       e..g. `app:get-user-daily-transactions 123 2023-12-31`")
            ->addArgument('user_id', InputArgument::REQUIRED, 'ID of the user')
            ->addArgument('date', InputArgument::REQUIRED, 'The date to generate report for (in the format \'yyyy-mm-dd\')')
            ->setDescription('Gets total transaction amount for a user for a given day');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('user_id');
        $date = $input->getArgument('date');

        $sum = $this->transactionService->getTotalTransactionAmountForUserPerDay((int)$userId, $date);
        $output->writeln("Total transaction amount for user {$userId} on {$date}: {$sum}");

        return Command::SUCCESS;
    }
}