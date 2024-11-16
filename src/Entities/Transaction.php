<?php

namespace Roomvu\Entities;

use DateTime;

class Transaction
{
    public function __construct(
        private int $id,
        private int $userId,
        private float $amount,
        private DateTime $date
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }
}