<?php

namespace App\Tests\Separator;

use App\Common\Model\Enum\TaskType;
use App\Common\Model\TaskCollection;
use App\Converter\Converter;
use App\Converter\Type\AccidentConverter;
use App\Converter\Type\InspectionConverter;
use App\Reader\Model\InputTask;
use App\Separator\Handler\ConverterHandler;
use App\Separator\Handler\ErrorHandler;
use App\Separator\Handler\HandlerFactory;
use App\Separator\Separator;
use App\Separator\Validator\Validator;
use App\Writer\Model\Accident;
use PHPUnit\Framework\TestCase;

class SeparatorTest extends TestCase
{
    public function testRealDataSeparation()
    {
        $sourceCollection = new TaskCollection([
            new InputTask([
                'number' => 1,
                'description' => 'Opis zwykłego zlecenia.',
                'dueDate' => new \DateTime('2020-01-04 13:30:00'),
                'phone' => '123456789',
            ]),
            new InputTask([
                'number' => 2,
                'description' => 'Opis zwykłego zlecenia.',
                'dueDate' => new \DateTime('2020-01-04 13:30:00'),
                'phone' => '123456789',
            ]),
            new InputTask([
                'number' => 3,
                'description' => 'Opis zwykłego zlecenia.',
                'dueDate' => 'it is error',
                'phone' => '123456789',
            ]),
        ]);
        $separator = new Separator(
            [TaskType::INSPECTION, TaskType::ACCIDENT],
            [ErrorHandler::class, ConverterHandler::class],
            new HandlerFactory(
                new Converter([
                    TaskType::INSPECTION => InspectionConverter::class,
                    TaskType::ACCIDENT => AccidentConverter::class,
                ]),
                new Validator()
            ),
        );
        $separator->process($sourceCollection);
        $targets = $separator->getTargets();
        $errors = $separator->getErrors();

        $result1 = $targets[TaskType::INSPECTION]->toArray();
        $this->assertCount(0, $result1);

        $result2 = $targets[TaskType::ACCIDENT]->toArray();
        $this->assertCount(2, $result2);
        $this->assertInstanceOf(Accident::class, $result2[0]);
        $this->assertInstanceOf(Accident::class, $result2[1]);

        $result3 = $errors->toArray();
        $this->assertCount(1, $errors);
        $this->assertInstanceOf(InputTask::class, $result3[0]);
    }
}
