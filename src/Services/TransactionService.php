<?php

namespace Roomvu\Services;

use DateTime;
use Exception;
use Roomvu\Entities\Transaction;
use Roomvu\Repositories\TransactionRepository;
use Roomvu\Repositories\UserRepository;
use Faker\Factory as Faker;
use RuntimeException;

class TransactionService
{
    private UserRepository $userRepository;
    private TransactionRepository $transactionRepository;

    public function __construct(
        UserRepository        $userRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * @throws Exception
     */
    public function createTransaction(int $userId, float $amount, string $date): Transaction
    {
        $user = $this->userRepository->find($userId);

        if ($user === null) {
            throw new RuntimeException('User with id ' . $userId . ' not found.');
        }

        if ($user->getCredit() < $amount) {
            throw new RuntimeException('Insufficient credit for transaction.');
        }

        $this->userRepository->update($userId, $user->getName(), $user->getEmail(), $user->getCredit() - $amount);
        return $this->transactionRepository->create($userId, $amount, $date);
    }


    public function getTransactionsForSpecificDay(int $userId, string $date): array
    {
        $user = $this->userRepository->find($userId);
        if ($user === null) {
            throw new RuntimeException('User with id ' . $userId . ' not found.');
        }

        return $this->transactionRepository->findTransactionsByUserIdAndDate($userId, $date);
    }

    public function getTotalTransactionAmountForUserPerDay(int $userId, string $date): float
    {
        $user = $this->userRepository->find($userId);
        if ($user === null) {
            throw new RuntimeException('User with id ' . $userId . ' not found.');
        }

        $total = $this->transactionRepository->findTransactionSumByUserIdAndDate($userId, $date);
        return $total ? floatval($total) : 0.0;
    }

    public function getTotalTransactionAmountForAllUsersPerDay(string $date): float
    {
        $total = $this->transactionRepository->findTransactionSumForAllUsersByDate($date);
        return $total ? floatval($total) : 0.0;
    }

    public function getTotalTransactionForUser(int $userId): float
    {
        $totalTransaction = $this->transactionRepository->findTransactionSumByUserId($userId);

        return $totalTransaction ? $totalTransaction : 0.0;
    }

    public function getTotalTransactionForAllUsers(): float
    {
        $totalTransaction = $this->transactionRepository->findTransactionSumForAllUsers();

        return $totalTransaction ? $totalTransaction : 0.0;
    }

    public function bulkInsert(int $times): void
    {
        $faker = Faker::create();
        $transactions = [];

        for ($i = 0; $i < $times; $i++) {

            $name = $faker->name();
            $email = $faker->email();
            $credit = $faker->randomFloat(2, 0, 1000);

            $newUser = $this->userRepository->create($name, $email, $credit);

            $transactionAmount = $faker->randomFloat(2, 0, $credit);

            if($transactionAmount > $newUser->getCredit()) {
                throw new RuntimeException('Insufficient credit for transaction.');
            }

            $newUser->setCredit($newUser->getCredit() - $transactionAmount);

            $this->userRepository->update($newUser->getId(), $newUser->getName(), $newUser->getEmail(), $newUser->getCredit());

            $transaction = new Transaction(
                0,
                $newUser->getId(),
                $transactionAmount,
                new DateTime()
            );

            $transactions[] = $transaction;
        }
        $this->transactionRepository->insert($transactions);
    }
}