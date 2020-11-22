<?php

namespace App\Reader\Type;

use App\Common\Model\Enum\Format;
use App\Reader\Exception\ReaderFactoryException;
use App\Reader\Serializer\InputTaskNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Serializer;

class FileReaderFactory implements ReaderFactoryInterface
{
    /**
     * @param string $type
     * @return ReaderInterface
     * @throws ReaderFactoryException
     */
    public function create(string $type): ReaderInterface
    {
        // for future use ;)
        switch ($type) {
            case Format::JSON:
                return $this->buildJsonReader();
            default:
                throw new ReaderFactoryException($type);
        }
    }

    protected function buildJsonReader(): JsonReader
    {
        $serializer = (new Serializer($this->getNormalizers(), $this->getEncoders()));

        return new JsonReader($serializer);
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
            new InputTaskNormalizer(),
            new ArrayDenormalizer(),
        ];
    }
}
