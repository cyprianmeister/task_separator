<?php

namespace App\Separator\Validator;

use App\Reader\Model\InputTask;

class Validator
{
    private array $processed = [];

    private array $errors = [];

    public function isValid(InputTask $inputTask): bool
    {
        if (null === $inputTask->getNumber()) {
            throw new ValidatorException('One of the input task hasn\'t got number or number is empty');
        }

        $messages[0] = $this->validateDueDate($inputTask);
        $messages[1] =  $this->validateNumber($inputTask);
        $messages[2] = $this->validateDuplicatedNumber($inputTask);

        $isValid = !(bool)$messages[0] && !(bool)$messages[1] && !(bool)$messages[2];

        $this->processed[] = $inputTask->getNumber();

        if (!$isValid) {
            $this->errors[] = [
                'number' => $inputTask->getNumber(),
                'message' => trim(implode(', ', $messages), ', '),
            ];
        }

        return $isValid;
    }

    private function validateDueDate(InputTask $inputTask): string
    {
        return is_string($inputTask->getDueDate()) ? 'Invalid dueDate' : '';
    }

    private function validateNumber(InputTask $inputTask): string
    {
        return is_string($inputTask->getNumber()) ? 'Invalid number' : '';
    }

    private function validateDuplicatedNumber(InputTask $inputTask): string
    {
        return in_array($inputTask->getNumber(), $this->processed) ? 'Duplicated number' : '';
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
