<?php

namespace App\Tests\Writer\Type;

use App\Common\Model\Enum\Format;
use App\Common\Model\TaskCollection;
use App\Writer\Model\OutputTask;
use App\Writer\Type\WriterFactory;
use App\Writer\Type\WriterInterface;
use PHPUnit\Framework\TestCase;

class JsonFileWriterTest extends TestCase
{
    private WriterInterface $jsonWriter;

    protected function setUp(): void
    {
        $factory = new WriterFactory();
        $this->jsonWriter = $factory->create(Format::JSON);
    }

    public function testWriteTaskCollection(): void
    {
        $target = __DIR__ . '/../../assets/result/target.json';
        $taskCollection = new TaskCollection([
            (new OutputTask())
                ->setDescription('test description')
                ->setComments('')
                ->setCreatedAt(new \DateTime('2020-01-01 01:01:01'))
                ->setDueDate(new \DateTime('2020-02-01 01:01:01'))
                ->setPhone('123456789')
                ->setStatus('example_state'),
            (new OutputTask())
                ->setDescription('')
                ->setComments('')
                ->setCreatedAt(new \DateTime('2020-01-01 01:01:01'))
                ->setDueDate(null)
                ->setPhone('')
                ->setStatus('')
        ]);

        $this->jsonWriter->setTarget($target)->write($taskCollection);

        $resultArray = json_decode(file_get_contents($target), true);
        $taskArray = $taskCollection->toArray();
        $collectionNum = $taskCollection->count();
        for ($c = 0; $c < $collectionNum; $c++) {
            $this->assertEquals($taskArray[$c]->getDescription(), $resultArray[$c]['description']);
            $this->assertEquals($taskArray[$c]->getComments(), $resultArray[$c]['comments']);
            $this->assertEquals($taskArray[$c]->getCreatedAt()->format('Y-m-d'), $resultArray[$c]['createdAt']);
            if ($dueDate = $taskArray[$c]->getDueDate()) {
                $dueDate = $dueDate->format('Y-m-d');
            }
            $this->assertEquals($dueDate, $resultArray[$c]['dueDate']);
            $this->assertEquals($taskArray[$c]->getPhone(), $resultArray[$c]['phone']);
        }
    }
}
