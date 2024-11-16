#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Roomvu\Console\Commands\CreateBulkTransactionsCommand;
use Roomvu\Console\Commands\CreateBulkUsersCommand;
use Roomvu\Console\Commands\GetAllUsersDailyTransactionsCommand;
use Roomvu\Console\Commands\GetUserDailyTransactionsCommand;
use Roomvu\Services\UserService;
use Roomvu\Console\Commands\CreateUserCommand;
use Symfony\Component\Console\Application;
use Roomvu\Console\Commands\CreateTransactionCommand;
use Roomvu\Services\TransactionService;
use Roomvu\Repositories\UserRepository;
use Roomvu\Repositories\TransactionRepository;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$userRepository = new UserRepository($pdo);
$transactionRepository = new TransactionRepository($pdo);

$transactionService = new TransactionService($userRepository, $transactionRepository);
$userService = new UserService($userRepository);

$application = new Application();

$application->add(new CreateTransactionCommand($transactionService));
$application->add(new CreateUserCommand($userService));
$application->add(new CreateBulkUsersCommand($userService));
$application->add(new CreateBulkTransactionsCommand($transactionService));
$application->add(new GetUserDailyTransactionsCommand($transactionService));
$application->add(new GetAllUsersDailyTransactionsCommand($transactionService));

$application->run();