<?php

namespace Roomvu\Tests\Controllers;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Roomvu\Controllers\UserConsoleController;
use Roomvu\Entities\User;
use Roomvu\Services\UserService;

class UserConsoleControllerTest extends TestCase
{
    private UserService|MockObject $userService;
    private UserConsoleController $controller;
    private User $user;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
        $this->controller = new UserConsoleController($this->userService);

        $this->user = new User(
            id: 1,
            name: 'Hossein Tehrani',
            email: 'hossein@tehrani.com',
            credit: 100.0
        );

        $this->updatedUser = new User(
            id: 1,
            name: 'Hossein Tehrani :-)',
            email: 'hossein@tehrani2.com',
            credit: 100.0
        );
    }

    public function testCreateUser(): void
    {
        $userData = [
            'id' => 1,
            'first_name' => 'Hossein',
            'last_name' => 'Tehrani',
            'email' => 'user@example.com',
        ];

        $this->userService->expects($this->once())
            ->method('createUser')
            ->with($userData)
            ->willReturn($this->user);

        ob_start();
        $this->controller->createUser($userData);
        $output = ob_get_clean();

        $userDetails = print_r($this->user, true);

        $expectedOutput = "User created. User details: \n" . $userDetails;
        $this->assertEquals($expectedOutput, $output);
    }

    public function testGetUser(): void
    {
        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        ob_start();
        $this->controller->getUser($this->user->getId());
        $output = ob_get_clean();

        $userDetails = print_r($this->user, true);
        $expectedOutput = "User details for ID: {$this->user->getId()}\n" . $userDetails;
        $this->assertEquals($expectedOutput, $output);
    }

    /**
     * @throws Exception
     */
    public function testUpdateUser(): void
    {
        $updatedData = [
            'id' => 1,
            'name' => 'Hossein Tehrani :-)',
            'email' => 'hossein@tehrani2.com',
            'credit' => 200.0,
        ];

        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        $updatedUser = new User(1, 'Hossein Tehrani :-)', 'hossein@tehrani2.com', 200.0);
        $this->userService->expects($this->once())
            ->method('updateUser')
            ->with($this->user, $updatedData)
            ->willReturn($updatedUser);

        ob_start();
        $this->controller->updateUser($this->user->getId(), $updatedData);
        $output = ob_get_clean();

        $userDetails = print_r($updatedUser, true);
        $expectedOutput = "User updated. Updated user details: \n" . $userDetails;
        $this->assertEquals($expectedOutput, $output);
    }

    public function testDeleteUser(): void
    {
        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        $this->userService->expects($this->once())
            ->method('deleteUser')
            ->with($this->user);

        ob_start();
        $this->controller->deleteUser($this->user->getId());
        $output = ob_get_clean();

        $expectedOutput = "User with id {$this->user->getId()} successfully deleted.\n";
        $this->assertEquals($expectedOutput, $output);
    }
}