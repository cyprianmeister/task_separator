<?php

namespace App\Tests\Reader\Type;

use App\Common\Model\Enum\Format;
use App\Reader\Exception\JsonReaderException;
use App\Reader\Model\InputTask;
use App\Reader\Type\FileReaderFactory;
use App\Reader\Type\ReaderInterface;
use PHPUnit\Framework\TestCase;

class JsonReaderTest extends TestCase
{
    private ReaderInterface $jsonReader;

    protected function setUp(): void
    {
        $factory = new FileReaderFactory();
        $this->jsonReader = $factory->create(Format::JSON);
    }

    public function testGetTaskCollection(): void
    {
        $source = __DIR__ . '/../../assets/task_source.json';
        $taskCollection = $this->jsonReader->read($source);

        $this->assertIsIterable($taskCollection);

        /** @var InputTask $task */
        foreach ($taskCollection as $task) {
            $this->assertInstanceOf(InputTask::class, $task);
            $this->assertIsInt($task->getNumber());
            if (in_array($task->getNumber(), [3, 4], true)) {
                $this->assertNull($task->getDueDate());
                $this->assertEquals('', $task->getPhone());
            } elseif ($task->getNumber() === 5) {
                $this->assertIsString($task->getDueDate());
            }
        }
    }

    public function testGetEmptyTaskCollection(): void
    {
        $source = __DIR__ . '/../../assets/task_source_empty.json';
        $taskCollection = $this->jsonReader->read($source);

        $this->assertIsIterable($taskCollection);
        $this->assertCount(0, $taskCollection);
    }

    public function testGetTaskCollectionFromJson(): void
    {
        $source = '[
            {
                "number":1,
                "description":"Opis",
                "dueDate":"2020-01-04 13:30:00",
                "phone":"123456789"
            }
        ]';
        $taskCollection = $this->jsonReader->read($source);

        $this->assertIsIterable($taskCollection);
        $this->assertCount(1, $taskCollection);
        $task = $taskCollection->current();
        $this->assertInstanceOf(InputTask::class, $task);
        $this->assertEquals(1, $task->getNumber());
        $this->assertEquals('Opis', $task->getDescription());
        $this->assertEquals('2020-01-04 13:30:00', $task->getDueDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('123456789', $task->getPhone());
    }

    public function testGetTaskCollectionIfSomeElementsNotExists(): void
    {
        $source = __DIR__ . '/../../assets/task_source_with_empty_elements.json';
        $taskCollection = $this->jsonReader->read($source);

        $this->assertIsIterable($taskCollection);
        $this->assertCount(1, $taskCollection);
        $task = $taskCollection->current();
        $this->assertInstanceOf(InputTask::class, $task);
        $this->assertNull($task->getNumber());
        $this->assertEquals('', $task->getDescription());
        $this->assertNull($task->getDueDate());
        $this->assertEquals('', $task->getPhone());
    }

    public function testGetTaskCollectionIfJsonIsIncorrect(): void
    {
        $source = __DIR__ . '/../../assets/task_source_with_json_error.json';
        $this->expectException(JsonReaderException::class);
        $this->jsonReader->read($source);
    }
}
