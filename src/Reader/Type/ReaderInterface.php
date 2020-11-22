<?php

namespace App\Reader\Type;

use App\Common\Model\TaskCollectionInterface;

interface ReaderInterface
{
    public function read(string $source): TaskCollectionInterface;
}
