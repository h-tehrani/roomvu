<?php

namespace Roomvu\Tests\Controllers;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Roomvu\Controllers\TransactionConsoleController;
use Roomvu\Entities\Transaction;
use Roomvu\Entities\User;
use Roomvu\Services\TransactionService;
use Roomvu\Services\UserService;
use PHPUnit\Framework\TestCase;

class TransactionConsoleControllerTest extends TestCase
{
    private MockObject|TransactionService $transactionService;
    private UserService|MockObject $userService;
    private TransactionConsoleController $controller;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->transactionService = $this->createMock(TransactionService::class);
        $this->userService = $this->createMock(UserService::class);
        $this->controller = new TransactionConsoleController($this->transactionService, $this->userService);

        $this->user = new User(1, 'Test User', 'hossein@tehrani.com', 100.0);
        $this->transaction = new Transaction(1, $this->user->getId(), 50.0, new \DateTime('2023-03-15'));
    }

    public function testCreateTransaction(): void
    {
        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        $this->transactionService->expects($this->once())
            ->method('createTransaction')
            ->with($this->user->getId(), 50.0, '2023-03-15')
            ->willReturn($this->transaction);

        ob_start();
        $this->controller->createTransaction($this->user->getId(), 50.0, '2023-03-15');
        $output = ob_get_clean();

        $transactionDetails = print_r($this->transaction, true);
        $expectedOutput = "Transaction created: \n" . $transactionDetails;
        $this->assertEquals($expectedOutput, $output);
    }


    public function testGetTransactionsForSpecificDay(): void
    {
        $date = '2023-03-15';
        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        $this->transactionService->expects($this->once())
            ->method('getTransactionsForSpecificDay')
            ->with($this->user->getId(), $date)
            ->willReturn([$this->transaction]);

        ob_start();
        $this->controller->getTransactionsForSpecificDay($this->user->getId(), $date);
        $output = ob_get_clean();

        $transactionDetails = print_r([$this->transaction], true);
        $expectedOutput = "Transactions for User ID: " . $this->user->getId() . " on {$date}: \n" . $transactionDetails;
        $this->assertEquals($expectedOutput, $output);
    }

    public function testGetTotalTransactionAmountForUserPerDay(): void
    {
        $date = '2023-03-15';
        $this->userService->expects($this->once())
            ->method('getUser')
            ->with($this->user->getId())
            ->willReturn($this->user);

        $this->transactionService->expects($this->once())
            ->method('getTotalTransactionAmountForUserPerDay')
            ->with($this->user->getId(), $date)
            ->willReturn($this->transaction->getAmount());

        ob_start();
        $this->controller->getTotalTransactionAmountForUserPerDay($this->user->getId(), $date);
        $output = ob_get_clean();

        $expectedOutput = "Total transaction amount for User ID: " . $this->user->getId() . " on {$date} is: \$" . $this->transaction->getAmount() . "\n";
        $this->assertEquals($expectedOutput, $output);
    }

    public function testGetTotalTransactionAmountForAllUsersPerDay(): void
    {
        $date = '2023-03-15';
        $this->transactionService->expects($this->once())
            ->method('getTotalTransactionAmountForAllUsersPerDay')
            ->with($date)
            ->willReturn($this->transaction->getAmount());

        ob_start();
        $this->controller->getTotalTransactionAmountForAllUsersPerDay($date);
        $output = ob_get_clean();

        $expectedOutput = "Total transaction amount for all users on {$date} is: \$" . $this->transaction->getAmount() . "\n";
        $this->assertEquals($expectedOutput, $output);
    }

}