<?php

namespace App;

use App\Common\Model\TaskCollectionInterface;

class Counter
{
    private array $counters = [];

    public function __construct(array $targets, TaskCollectionInterface $errors)
    {
        $this->count($targets, $errors);
    }

    public function count(array $targets, TaskCollectionInterface $errors): void
    {
        /**
         * @var string $key
         * @var  TaskCollectionInterface $target
         */
        foreach ($targets as $key => $target) {
            $this->counters[$key] = $target->count();
        }

        $this->counters['errors'] = $errors->count();
    }

    public function getCounters(): array
    {
        return $this->counters;
    }

    public function getSum(): int
    {
        return array_sum($this->counters);
    }
}
