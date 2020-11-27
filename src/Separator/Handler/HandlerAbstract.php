<?php

namespace App\Separator\Handler;

use App\Reader\Model\InputTask;
use App\Separator\Solution;

abstract class HandlerAbstract implements HandlerInterface
{
    private ?HandlerInterface $handler = null;

    public function handle(?InputTask $inputTask): Solution
    {
        return $this->handler
            ? $this->handler->handle($inputTask)
            : new Solution();
    }

    public function nextStep(HandlerInterface $handler): HandlerInterface
    {
        $this->handler = $handler;
        return $handler;
    }
}
