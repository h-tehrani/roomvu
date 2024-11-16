<?php

namespace Roomvu\Controllers;

use Roomvu\Services\TransactionService;
use Roomvu\Services\UserService;

class TransactionConsoleController
{
    protected TransactionService $transactionService;
    protected UserService $userService;

    public function __construct(TransactionService $transactionService, UserService $userService)
    {
        $this->transactionService = $transactionService;
        $this->userService = $userService;
    }

    public function createTransaction(int $userId, float $amount, string $date): void
    {
        try {
            $user = $this->userService->getUser($userId);
            $transaction = $this->transactionService->createTransaction($user->getId(), $amount, $date);
            echo "Transaction created: \n" . print_r($transaction, true);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function getTransactionsForSpecificDay(int $userId, string $date): void
    {
        try {
            $user = $this->userService->getUser($userId);
            $transactions = $this->transactionService->getTransactionsForSpecificDay($user->getId(), $date);
            echo "Transactions for User ID: {$userId} on {$date}: \n" . print_r($transactions, true);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function getTotalTransactionAmountForUserPerDay(int $userId, string $date): void
    {
        try {
            $user = $this->userService->getUser($userId);
            $total = $this->transactionService->getTotalTransactionAmountForUserPerDay($user->getId(), $date);
            echo "Total transaction amount for User ID: {$userId} on {$date} is: \${$total}\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function getTotalTransactionAmountForAllUsersPerDay(string $date): void
    {
        try {
            $total = $this->transactionService->getTotalTransactionAmountForAllUsersPerDay($date);
            echo "Total transaction amount for all users on {$date} is: \${$total}\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}