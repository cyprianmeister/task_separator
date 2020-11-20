<?php

namespace App\Writer\Model;

use App\Common\Model\Enum\TaskType;

class Inspection extends OutputTask
{
    protected static string $type = TaskType::INSPECTION;

    protected ?int $weekOfYear;

    public function setWeekOfYear(?int $weekOfYear): Inspection
    {
        $this->weekOfYear = $weekOfYear;
        return $this;
    }
}
