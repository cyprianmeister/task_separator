<?php

namespace App\Converter\Rule;

use App\Common\Model\Enum\TaskType;
use App\Converter\Tool\DescriptionRuleTrait;
use App\Reader\Model\InputTask;

class TypeRule implements RuleInterface
{
    use DescriptionRuleTrait;

    private const SEARCHED_WORDS = ['przegląd', 'przeglądy'];

    public function convert(InputTask $inputTask): string
    {
        foreach ($this->getDescriptionWords($inputTask) as $word) {
            $word = strtolower($word);
            if (levenshtein($word, self::SEARCHED_WORDS[0]) < 4 || levenshtein($word, self::SEARCHED_WORDS[1]) < 4) {
                $value = TaskType::INSPECTION;
                break;
            }
        }

        return $value ?? TaskType::ACCIDENT;
    }
}
