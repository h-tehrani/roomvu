<?php

namespace Roomvu\Repositories;

use DateTime;
use Exception;
use Roomvu\Entities\Transaction;
use PDO;

class TransactionRepository
{
    /**
     * An instance of the PDO class representing a database connection
     */
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @throws Exception
     */
    public function find(int $id): ?Transaction
    {
        $stmt = $this->pdo->prepare('SELECT * FROM transactions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if (!$data) {
            return null;
        }

        return new Transaction(
            $data['id'],
            $data['user_id'],
            $data['amount'],
            new DateTime($data['date'])
        );
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM transactions WHERE id = :id');
        $res = $stmt->execute(['id' => $id]);

        return $res !== false && $stmt->rowCount() > 0;
    }

    /**
     * @throws Exception
     */
    public function create(int $userId, float $amount, string $date): Transaction
    {
        $stmt = $this->pdo->prepare('INSERT INTO transactions (user_id, amount, date) VALUES (:userId, :amount, :date)');
        $stmt->execute(['userId' => $userId, 'amount' => $amount, 'date' => $date]);

        return $this->find($this->pdo->lastInsertId());
    }


    public function findTransactionSumByUserId(int $userId): ?float
    {
        $stmt = $this->pdo->prepare('SELECT SUM(amount) as total FROM transactions WHERE user_id = :userId');
        $stmt->execute(['userId' => $userId]);

        $data = $stmt->fetch();

        return $data ? $data['total'] : null;
    }

    public function findTransactionSumForAllUsers(): ?float
    {
        $stmt = $this->pdo->prepare('SELECT SUM(amount) as total FROM transactions');
        $stmt->execute();

        $data = $stmt->fetch();

        return $data ? $data['total'] : null;
    }

    public function findTransactionsByUserIdAndDate(int $userId, string $date): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM transactions WHERE user_id = :userId AND DATE(date) = :date'
        );

        $stmt->execute([':userId' => $userId, ':date' => $date]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findTransactionSumByUserIdAndDate(int $userId, string $date): ?string
    {
        $stmt = $this->pdo->prepare(
            'SELECT SUM(amount) as total FROM transactions WHERE user_id = :userId AND DATE(date) = :date'
        );
        $stmt->execute([':userId' => $userId, ':date' => $date]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return $row['total'];
    }


    public function findTransactionSumForAllUsersByDate(string $date): ?string
    {
        $stmt = $this->pdo->prepare(
            'SELECT SUM(amount) as total FROM transactions WHERE DATE(date) = :date'
        );

        $stmt->execute([':date' => $date]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $row['total'];
    }


    public function insert(array $transactions): void
    {
        $sql = 'INSERT INTO transactions (user_id, amount, date) VALUES ';
        $parameters = [];

        foreach ($transactions as $index => $transaction) {
            $sql .= "(:userId{$index}, :amount{$index}, :date{$index}),";

            $parameters += [
                "userId{$index}" => $transaction->getUserId(),
                "amount{$index}" => $transaction->getAmount(),
                "date{$index}" => $transaction->getDate()->format('Y-m-d H:i:s'),
            ];
        }

        $sql = rtrim($sql, ',');

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($parameters);
    }
}