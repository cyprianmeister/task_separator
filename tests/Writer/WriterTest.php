<?php

namespace App\Tests\Writer;

use App\Common\Model\Enum\Format;
use App\Common\Model\TaskCollectionInterface;
use App\Writer\Type\JsonFileWriter;
use App\Writer\Type\WriterFactoryInterface;
use App\Writer\Type\WriterInterface;
use App\Writer\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    public function testSelectWriter(): void
    {
        $target = 'example_target';
        $collection = $this->getMockBuilder(TaskCollectionInterface::class)
            ->setMockClassName('Collection')
            ->getMock();
        $types = ['type1', 'type2'];

        $writers = [];
        foreach ($types as $type) {
            $writers[$type] = $this->getMockBuilder(WriterInterface::class)
                ->setMockClassName('Writer'.$type)
                ->getMock();
            $writers[$type]->expects($this->once())->method('setTarget')->with($target)->willReturnSelf();
            $writers[$type]->expects($this->once())->method('write')->with($collection);
        }

        $factory = $this->createMock(WriterFactoryInterface::class);
        $factory->expects($this->exactly(2))->method('create')
            ->with($this->logicalOr($this->equalTo($types[0]), $this->equalTo($types[1])))
            ->willReturnCallback(fn(string $type) => $writers[$type]);

        $writer = new Writer($factory);
        $writer->setType($types[0])->write($collection, $target);
        $writer->setType($types[1])->write($collection, $target);
    }

    public function testJsonFileWrite(): void
    {
        $target = 'example_target.json';
        $taskCollection = $this->createMock(TaskCollectionInterface::class);

        $jsonWriter = $this->createMock(JsonFileWriter::class);
        $jsonWriter->expects($this->once())->method('setTarget')->with($target)->willReturnSelf();
        $jsonWriter->expects($this->once())->method('write')->with($taskCollection);

        $factory = $this->createMock(WriterFactoryInterface::class);
        $factory->expects($this->once())->method('create')->with(Format::JSON)->willReturn($jsonWriter);

        $writer = new Writer($factory);
        $writer->setType(Format::JSON)->write($taskCollection, $target);
    }

    public function testJsonWriteWithoutSetStrategy(): void
    {
        $target = 'example_target.json';
        $taskCollection = $this->createMock(TaskCollectionInterface::class);

        $jsonWriter = $this->createMock(JsonFileWriter::class);
        $jsonWriter->expects($this->never())->method('setTarget')->withAnyParameters();
        $jsonWriter->expects($this->never())->method('write')->withAnyParameters();

        $factory = $this->createMock(WriterFactoryInterface::class);
        $factory->expects($this->never())->method('create')->with(Format::JSON)->willReturn($jsonWriter);

        $this->expectException(\Error::class);
        $writer = new Writer($factory);
        $writer->write($taskCollection, $target);
    }
}
