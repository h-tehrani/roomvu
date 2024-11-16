<?php

namespace Roomvu\Repositories;

use Roomvu\Entities\User;
use PDO;
use RuntimeException;

class UserRepository
{
    /**
     * An instance of the PDO class representing a database connection
     */
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function find(int $id): ?User
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if (!$data) {
            throw new RuntimeException('User with id ' . $id . ' not found.');
        }

        return new User(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['credit']
        );
    }

    /**
     * Delete a user from the database.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $res = $stmt->execute(['id' => $id]);

        return $res !== false && $stmt->rowCount() > 0;
    }

    /**
     * Insert a new user in database
     */
    public function create(string $name, string $email, float $credit = 0): User // Default credit set to 0
    {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, credit) VALUES (:name, :email, :credit)');
        $stmt->execute(['name' => $name, 'email' => $email, 'credit' => $credit]);

        return $this->find($this->pdo->lastInsertId());
    }

    public function update(int $id, string $name, string $email, float $credit): User
    {
        $stmt = $this->pdo->prepare('UPDATE users SET name = :name, email = :email, credit = :credit WHERE id = :id');
        $stmt->execute(['name' => $name, 'email' => $email, 'credit' => $credit, 'id' => $id]);

        return $this->find($id);
    }

    public function insert(array $usersData): void
    {
        $sql = "INSERT INTO users (name, email, credit) VALUES (:name, :email, :credit)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($usersData as $userData) {
            $stmt->bindParam(':name', $userData['name']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':credit', $userData['credit']);

            $stmt->execute();
        }
    }
}