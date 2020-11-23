<?php

namespace App\Writer\Type;

use App\Common\Model\Enum\Format;
use App\Writer\Exception\WriterFactoryException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class WriterFactory implements WriterFactoryInterface
{
    private const DUE_DATE_FORMAT = 'Y-m-d';

    /**
     * @param string $type
     * @return WriterInterface
     * @throws WriterFactoryException
     */
    public function create(string $type): WriterInterface
    {
        // for future use ;)
        switch ($type) {
            case Format::JSON:
                return $this->buildJsonWriter();
            default:
                throw new WriterFactoryException($type);
        }
    }

    protected function buildJsonWriter(): JsonFileWriter
    {
        $serializer = (new Serializer($this->getNormalizers(), $this->getEncoders()));

        return new JsonFileWriter($serializer);
    }

    private function getEncoders(): array
    {
        return [
            new JsonEncoder(),
        ];
    }

    private function getNormalizers(): array
    {
        return [
            new ArrayDenormalizer(),
            new DateTimeNormalizer([DateTimeNormalizer::FORMAT_KEY => self::DUE_DATE_FORMAT]),
            new GetSetMethodNormalizer(),
        ];
    }
}
