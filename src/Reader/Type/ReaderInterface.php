<?php

namespace App\Reader\Type;

use App\Reader\Model\TaskCollectionInterface;

interface ReaderInterface
{
    public function read(string $source): TaskCollectionInterface;
}
