<?php

namespace App\Converter\Tool;

use App\Reader\Model\InputTask;

trait DescriptionRuleTrait
{
    private function getDescriptionWords(InputTask $inputTask): array
    {
        $description = $inputTask->getDescription();
        return array_values(array_filter(
            explode(' ', str_replace([',','.','?','!',':',';'], ' ', $description)),
            fn (string $item) => $item !== ''
        ));
    }
}
