<?php

namespace App\Tests\Reader;

use App\Common\Model\Enum\Format;
use App\Reader\Model\TaskCollectionInterface;
use App\Reader\Reader;
use App\Reader\Type\JsonReader;
use App\Reader\Type\ReaderFactoryInterface;
use App\Reader\Type\ReaderInterface;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    public function testSelectReader(): void
    {
        $source = 'example_source';
        $types = ['type1', 'type2'];

        $readers = [];
        $collections = [];
        foreach ($types as $type) {
            $collections[$type] = $this->getMockBuilder(TaskCollectionInterface::class)
                ->setMockClassName('Collection'.$type)
                ->getMock();
            $readers[$type] = $this->getMockBuilder(ReaderInterface::class)
                ->setMockClassName('Reader'.$type)
                ->getMock();
            $readers[$type]->expects($this->once())->method('read')->with($source)->willReturn($collections[$type]);
        }

        $factory = $this->createMock(ReaderFactoryInterface::class);
        $factory->expects($this->exactly(2))->method('create')
            ->with($this->logicalOr($this->equalTo($types[0]), $this->equalTo($types[1])))
            ->willReturnCallback(fn(string $type) => $readers[$type]);

        $reader = new Reader($factory);
        $result1 = $reader->setType($types[0])->read($source);
        $result2 = $reader->setType($types[1])->read($source);

        $this->assertEquals($collections[$types[0]], $result1);
        $this->assertNotEquals($collections[$types[1]], $result1);
        $this->assertEquals($collections[$types[1]], $result2);
        $this->assertNotEquals($collections[$types[0]], $result2);
    }

    public function testJsonRead(): void
    {
        $source = 'example_source.json';
        $taskCollection = $this->createMock(TaskCollectionInterface::class);

        $jsonReader = $this->createMock(JsonReader::class);
        $jsonReader->expects($this->once())->method('read')->with($source)->willReturn($taskCollection);

        $factory = $this->createMock(ReaderFactoryInterface::class);
        $factory->expects($this->once())->method('create')->with(Format::JSON)->willReturn($jsonReader);

        $reader = new Reader($factory);
        $result = $reader->setType(Format::JSON)->read($source);

        $this->assertEquals($taskCollection, $result);
    }

    public function testJsonReadWithoutSetStrategy(): void
    {
        $source = 'example_source.json';
        $taskCollection = $this->createMock(TaskCollectionInterface::class);

        $jsonReader = $this->createMock(JsonReader::class);
        $jsonReader->expects($this->never())->method('read')->with($source)->willReturn($taskCollection);

        $factory = $this->createMock(ReaderFactoryInterface::class);
        $factory->expects($this->never())->method('create')->with(Format::JSON)->willReturn($jsonReader);

        $this->expectException(\Error::class);
        $reader = new Reader($factory);
        $reader->read($source);
    }
}
