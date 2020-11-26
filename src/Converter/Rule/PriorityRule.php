<?php

namespace App\Converter\Rule;

use App\Common\Model\Enum\Priority;
use App\Converter\Tool\DescriptionRuleTrait;
use App\Reader\Model\InputTask;

class PriorityRule implements RuleInterface
{
    use DescriptionRuleTrait;

    private const SEARCHED_WORDS = ['bardzo', 'pilne'];

    public function convert(InputTask $inputTask): string
    {
        $elements = $this->getDescriptionWords($inputTask);
        foreach($elements as $key => $word) {
            if (levenshtein($word, self::SEARCHED_WORDS[1]) < 3) {
                return $key - 1 > -1 && levenshtein($elements[$key - 1], self::SEARCHED_WORDS[0]) < 3
                    ? Priority::CRITICAL
                    : Priority::HIGH;
            }
        }

        return Priority::NORMAL;
    }
}
