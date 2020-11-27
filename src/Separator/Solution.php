<?php

namespace App\Separator;

use App\Common\Model\BaseTask;

final class Solution
{
    private ?string $type;

    private ?BaseTask $task;

    public function __construct(?BaseTask $task = null, ?string $type = null)
    {
        $this->type = $type;
        $this->task = $task;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getTask(): ?BaseTask
    {
        return $this->task;
    }
}
