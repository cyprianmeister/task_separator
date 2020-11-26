<?php

namespace App\Common\Model;

interface TaskCollectionInterface extends \Iterator, \Countable
{
    public function toArray(): array;

    public function getUniqueByCallback(callable $callback): TaskCollectionInterface;

    public function add(BaseTask $task): void;

    public function take(int $key): ?BaseTask;
}
