<?php

namespace Roomvu\Controllers;

use Roomvu\Services\UserService;

class UserConsoleController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createUser(array $data): void
    {
        $user = $this->userService->createUser($data);
        echo "User created. User details: \n" . print_r($user, true);
    }

    public function getUser(int $id): void
    {
        $user = $this->userService->getUser($id);
        echo "User details for ID: {$id}\n" . print_r($user, true);
    }

    public function updateUser(int $id, array $data): void
    {
        try {
            $user = $this->userService->getUser($id);
            $updatedUser = $this->userService->updateUser($user, $data);
            echo "User updated. Updated user details: \n" . print_r($updatedUser, true);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function deleteUser(int $id): void
    {
        try {
            $user = $this->userService->getUser($id);
            $this->userService->deleteUser($user);
            echo "User with id {$id} successfully deleted.\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    public function insert(int $times): void
    {
        try {
            $this->userService->bulkInsert($times);
            echo "Users inserted successfully.\n";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }
}