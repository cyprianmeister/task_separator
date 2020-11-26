<?php

namespace App\Converter\Type;

use App\Converter\Rule\DescriptionRule;
use App\Converter\Rule\DueDateRule;
use App\Converter\Rule\InspectionStatusRule;
use App\Converter\Rule\PhoneRule;
use App\Converter\Rule\PriorityRule;
use App\Converter\Rule\RuleInterface;
use App\Reader\Model\InputTask;
use App\Writer\Model\Inspection;
use App\Writer\Model\OutputTask;

class InspectionConverter implements ConverterInterface
{
    private array $rules = [
        InspectionStatusRule::class => 'status',
        PriorityRule::class => 'priority',
        DueDateRule::class => 'dueDate',
        DescriptionRule::class => 'description',
        PhoneRule::class => 'phone',
    ];

    public function convert(InputTask $inputTask): OutputTask
    {
        $outputTask = new Inspection();
        $this->runRules($inputTask, $outputTask);

        return $outputTask;
    }

    private function runRules(InputTask $inputTask, OutputTask $outputTask): void
    {
        foreach ($this->getRules() as $rule) {
            $value = $rule->convert($inputTask);
            $setter = $this->setter($rule);
            if (method_exists($outputTask, $setter)) {
                $outputTask->$setter($value);
            }
        }
    }

    private function getRules(): \Generator
    {
        foreach (array_keys($this->rules) as $rule) {
            yield new $rule();
        }
    }

    private function setter(RuleInterface $rule): string
    {
        return 'set' . ucfirst($this->rules[get_class($rule)]);
    }
}
