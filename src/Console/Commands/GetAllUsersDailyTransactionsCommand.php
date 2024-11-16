<?php

namespace Roomvu\Console\Commands;

use AllowDynamicProperties;
use Psr\Cache\InvalidArgumentException;
use Roomvu\Services\TransactionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

#[AllowDynamicProperties]
class GetAllUsersDailyTransactionsCommand extends Command
{
    protected static string $defaultName = 'app:get-all-users-daily-transactions';

    private TransactionService $transactionService;
    private FilesystemAdapter $cache;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
        $this->cache = new FilesystemAdapter(
            'app_cache',
            0,
            __DIR__.'/../../../cache'
        );
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:get-all-users-daily-transactions')
            ->setDescription('Generates a report of total transaction amounts for all users for a given day')
            ->setHelp("This command allows you to receive a total transactions sum for all users for a given day. 
                       You must provide the date (in the format 'yyyy-mm-dd') when calling this command, 
                       e.g. `app:get-all-users-daily-transactions 2023-12-31`")
            ->addArgument('date', InputArgument::REQUIRED, 'The date to generate the report for (in the format \'yyyy-mm-dd\')');
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $date = $input->getArgument('date');

        $cacheKey = 'total_transaction_' . str_replace('-', '_', $date);

        $item = $this->cache->getItem($cacheKey);

        if (!$item->isHit()){
            $sum = $this->transactionService->getTotalTransactionAmountForAllUsersPerDay($date);
            $item->set($sum);
            $this->cache->save($item);
            $output->writeln("[INFO] Item wasn found in the cache. Computed the sum and stored it to cache for future use.");
        } else {
            $sum = $item->get();
            $output->writeln("[INFO] Item was found in the cache. No computation needed.");
        }

        $output->writeln("Total transaction amount for all users on {$date}: {$sum}");

        return Command::SUCCESS;
    }
}