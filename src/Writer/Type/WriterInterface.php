<?php

namespace App\Writer\Type;

use App\Common\Model\TaskCollectionInterface;

interface WriterInterface
{
    public function setTarget($target): WriterInterface;

    public function write(TaskCollectionInterface $taskCollection): void;
}
