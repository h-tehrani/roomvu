<?php

namespace Roomvu\Entities;

class User
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private float $credit
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCredit(): float
    {
        return $this->credit;
    }

    public function setCredit(float $credit): void
    {
        $this->credit = $credit;
    }
}