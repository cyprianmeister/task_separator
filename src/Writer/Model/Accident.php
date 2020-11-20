<?php

namespace App\Writer\Model;

use App\Common\Model\Enum\TaskType;

class Accident extends OutputTask
{
    protected static string $type = TaskType::ACCIDENT;

    protected string $priority;

    public function setPriority(string $priority): Accident
    {
        $this->priority = $priority;
        return $this;
    }
}
