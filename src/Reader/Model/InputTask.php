<?php

namespace App\Reader\Model;

use App\Common\Model\BaseTask;

class InputTask extends BaseTask
{
    protected int $number;

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->dueDate;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
