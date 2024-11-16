<?php

namespace Roomvu\Console\Commands;

use Exception;
use Roomvu\Services\TransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTransactionCommand extends Command
{
    protected static string $defaultName = 'app:create-transaction';
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-transaction')
            ->setDescription('Creates a new transaction.')
            ->setHelp('This command allows you to create a transaction...')
            ->addArgument('user_id', InputArgument::REQUIRED, 'The ID of the user.')
            ->addArgument('amount', InputArgument::REQUIRED, 'The amount of the transaction.')
            ->addArgument('date', InputArgument::REQUIRED, 'The date of the transaction.');
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('user_id');
        $amount = $input->getArgument('amount');
        $date = $input->getArgument('date');

        $this->transactionService->createTransaction($userId, $amount, $date);

        $output->writeln("Transaction created for User ID $userId on $date with amount: $amount.");

        return Command::SUCCESS;
    }
}