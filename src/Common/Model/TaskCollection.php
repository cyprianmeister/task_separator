<?php

namespace App\Common\Model;

class TaskCollection implements TaskCollectionInterface
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = array_values($items);
    }

    /**
     * @return BaseTask|false
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @return BaseTask|false
     */
    public function next()
    {
        return next($this->items);
    }

    public function key(): ?int
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return isset($this->items[$this->key()]);
    }

    /**
     * @return BaseTask|false
     */
    public function rewind()
    {
        return reset($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }
}