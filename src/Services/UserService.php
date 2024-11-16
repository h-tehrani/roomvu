<?php

namespace Roomvu\Services;

use Faker\Factory as Faker;
use Roomvu\Repositories\UserRepository;
use Roomvu\Entities\User;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function bulkInsert(int $times): void
    {
        $faker = Faker::create();
        $usersData = [];
        for ($i = 0; $i < $times; $i++) {
            $usersData[] = [
                'name' => $faker->name(),
                'email' => $faker->email(),
                'credit' => $faker->randomFloat(2, 0, 1000)
            ];
        }
        $this->userRepository->insert($usersData);
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data['name'], $data['email'], $data['credit']);
    }

    public function getUser(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function updateUser(User $user, array $data): User
    {
        return $this->userRepository->update($user['id'], $data['name'], $data['email'], $user->getCredit());
    }

    public function deleteUser(User $user): void
    {
        $deleted = $this->userRepository->delete($user->getId());

        if (!$deleted) {
            throw new \RuntimeException('User with id ' . $user->getId() . ' not found.');
        }
    }
}
