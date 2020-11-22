<?php

namespace App\Reader\Serializer;

use App\Reader\Model\InputTask;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class InputTaskNormalizer implements DenormalizerInterface
{
    private const DUE_DATE_FORMATS = [
        'Y-m-d',
        'Y-m-d H:i:s',
    ];

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (isset($data['number']) && !is_int($data['number'])) {
            $data['number'] = (string)$data['number'];
        }

        if (isset($data['dueDate']) && is_string($data['dueDate'])) {
            $data['dueDate'] = $this->getDate($data['dueDate']);
        }

        $data['description'] = $this->getDescription($data);
        $data['phone'] = $data['phone'] ?? '';
        $data['isValid'] = $this->getValidation($data);

        return new $type($data);
    }

    /**
     * @param string $formattedDate
     * @return \DateTime|string|null
     */
    private function getDate(string $formattedDate)
    {
        if ($formattedDate === '') {
            return null;
        }

        foreach (self::DUE_DATE_FORMATS as $format) {
            $date = \DateTime::createFromFormat($format, $formattedDate);
            if ($date && $date->format($format) === $formattedDate) {
                return $date;
            }
        }

        return $formattedDate;
    }

    private function getDescription(array $data): string
    {
        if (isset($data['description']) && is_string($data['description'])) {
            $data['description'] = trim($data['description']);
        }

        return $data['description'] ?? '';
    }

    private function getValidation(array $data): bool
    {
        $dueDateIsValid = !array_key_exists('dueDate', $data) || !is_string($data['dueDate']);
        $numberIsValid = isset($data['number']) && !is_string($data['number']);

        return $dueDateIsValid && $numberIsValid;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_array($data) && $type === InputTask::class;
    }
}
