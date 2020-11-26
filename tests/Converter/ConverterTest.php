<?php

namespace App\Tests\Converter;

use App\Common\Model\Enum\AccidentStatus;
use App\Common\Model\Enum\InspectionStatus;
use App\Common\Model\Enum\Priority;
use App\Common\Model\Enum\TaskType;
use App\Converter\Converter;
use App\Converter\Exception\ConverterException;
use App\Converter\Type\AccidentConverter;
use App\Converter\Type\InspectionConverter;
use App\Reader\Model\InputTask;
use App\Writer\Model\Accident;
use App\Writer\Model\Inspection;
use App\Writer\Model\OutputTask;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    public function testConvertInputTaskToInspection(): void
    {
        $source = [
            'number' => 1,
            'description' => 'Opis zwykłego zlecenia. Jest to przegląd.',
            'dueDate' =>  new \DateTime('2020-01-04 13:30:00'),
            'phone' => '123456789',
        ];

        $target = [
            'type' => TaskType::INSPECTION,
            'description' => $source['description'],
            'dueDate' => $source['dueDate'],
            'status' => InspectionStatus::PLANNED,
            'phone' => $source['phone'],
            'comments' => '',
            'weekOfYear' => 1,
        ];

        $this->check($source, $target, Inspection::class);
    }

    public function testConvertInputTaskToInspectionWithoutDueDateAndPhone(): void
    {
        $source = [
            'number' => 1,
            'description' => 'Opis zwykłego zlecenia. Jest to przegląd.',
            'dueDate' =>  null,
            'phone' => '',
        ];

        $target = [
            'type' => TaskType::INSPECTION,
            'description' => $source['description'],
            'dueDate' => null,
            'status' => InspectionStatus::NEW,
            'phone' => $source['phone'],
            'comments' => '',
            'weekOfYear' => null,
        ];

        $this->check($source, $target, Inspection::class);
    }

    public function testConvertInputTaskToAccident(): void
    {
        $source = [
            'number' => 1,
            'description' => 'Opis zwykłego zlecenia.',
            'dueDate' =>  new \DateTime('2020-01-04 13:30:00'),
            'phone' => '123456789',
        ];

        $target = [
            'type' => TaskType::ACCIDENT,
            'description' => $source['description'],
            'dueDate' => $source['dueDate'],
            'status' => AccidentStatus::TERM,
            'phone' => $source['phone'],
            'comments' => '',
            'priority' => Priority::NORMAL,
        ];

        $this->check($source, $target, Accident::class);
    }

    public function testConvertInputTaskToAccidentWithoutDueDateAndPhone(): void
    {
        $source = [
            'number' => 1,
            'description' => 'Opis zwykłego zlecenia.',
            'dueDate' =>  null,
            'phone' => '',
        ];

        $target = [
            'type' => TaskType::ACCIDENT,
            'description' => $source['description'],
            'dueDate' => null,
            'status' => AccidentStatus::NEW,
            'phone' => $source['phone'],
            'comments' => '',
            'priority' => Priority::NORMAL,
        ];

        $this->check($source, $target, Accident::class);
    }

    public function testConverterIfTypeNotExists(): void
    {
        $inputTask = new InputTask([
            'number' => 1,
            'description' => 'Opis zwykłego zlecenia.',
            'dueDate' =>  new \DateTime('2020-01-04 13:30:00'),
            'phone' => '123456789',
        ]);

        $converter = new Converter([
            TaskType::INSPECTION => InspectionConverter::class,
        ]);

        $this->expectException(ConverterException::class);

        /** @var Inspection $outputTask */
        $outputTask = $converter->convert($inputTask);
    }

    private function check(array $source, array $target, string $class): void
    {
        $inputTask = new InputTask($source);

        $converter = new Converter([
            TaskType::INSPECTION => InspectionConverter::class,
            TaskType::ACCIDENT => AccidentConverter::class,
        ]);

        /** @var Inspection $outputTask */
        $outputTask = $converter->convert($inputTask);

        $this->assertInstanceOf($class, $outputTask);

        $this->assertEqualsProperties($outputTask, $target);
    }

    private function assertEqualsProperties(OutputTask $outputTask, array $properties): void
    {
        foreach ($properties as $key => $value) {
            $method = 'get' . ucfirst($key);
            if ($key === 'createdAt') {
                $createdAt = $outputTask->$method();
                $this->assertInstanceOf(\DateTime::class, $createdAt);
                continue;
            }
            $this->assertEquals($value, $outputTask->$method());
        }
    }
}
