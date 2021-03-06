<?php

namespace App\Common\Model;

use App\Tool\Logger;

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

    public function toArray(): array
    {
        return $this->items;
    }

    public function getUniqueByCallback(callable $callback): TaskCollectionInterface
    {
        $mappedItems = array_map($callback, $this->items);
        $keys = array_keys(array_unique($mappedItems));
        $filteredItems = array_filter($this->items, fn (int $key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);

        $duplicates = array_diff($this->mapToNumbers($this->items), $this->mapToNumbers($filteredItems));
        Logger::use()->log('Remove duplicates', ['duplicates' => implode(', ', $duplicates)]);

        return new TaskCollection($filteredItems);
    }

    public function add(BaseTask $task): void
    {
        $this->items[] = $task;
    }

    public function get(int $key): ?BaseTask
    {
        return $this->items[$key] ?? null;
    }

    private function mapToNumbers(array $items): array
    {
        return array_map(
            fn (BaseTask $item) => (method_exists($item, 'getNumber') ? $item->getNumber() : 'null'),
            $items
        );
    }
}
