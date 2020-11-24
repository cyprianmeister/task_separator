<?php

namespace App\Tests\Common\Model;

use App\Common\Model\TaskCollection;
use App\Reader\Model\InputTask;
use PHPUnit\Framework\TestCase;

class TaskCollectionTest extends TestCase
{
    public function testGetUniqueItemsByCallback(): void
    {
        $source = [
            new InputTask(['number' => 1]),
            new InputTask(['number' => 2]),
            new InputTask(['number' => 1]),
            new InputTask(['number' => 3]),
            new InputTask(['number' => 1]),
        ];

        $collection = new TaskCollection($source);
        $uniqueCollection = $collection->getUniqueByCallback(fn (InputTask $item) => $item->getNumber());
        $result = $uniqueCollection->toArray();

        $this->assertCount(3, $result);
        $this->assertEquals($source[0], $result[0]);
        $this->assertEquals($source[1], $result[1]);
        $this->assertEquals($source[3], $result[2]);
    }
}
