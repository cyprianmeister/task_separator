<?php

namespace App\Common\Model;

interface TaskCollectionInterface extends \Iterator, \Countable
{
    public function toArray(): array;
}
