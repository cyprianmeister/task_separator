<?php

namespace App\Separator\Handler;

use App\Reader\Model\InputTask;
use App\Separator\Solution;

interface HandlerInterface
{
    public function handle(?InputTask $inputTask): Solution;

    public function nextStep(HandlerInterface $handler): HandlerInterface;
}
