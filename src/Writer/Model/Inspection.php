<?php

namespace App\Writer\Model;

use App\Common\Model\Enum\TaskType;

class Inspection extends OutputTask
{
    protected static string $type = TaskType::INSPECTION;

    public function getWeekOfYear(): ?int
    {
        return $this->dueDate ? (int)$this->dueDate->format('W') : null;
    }
}
