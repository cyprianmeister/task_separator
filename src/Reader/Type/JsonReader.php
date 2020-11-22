<?php

namespace App\Reader\Type;

use App\Common\Model\Enum\Format;
use App\Reader\Exception\JsonReaderException;
use App\Reader\Model\InputTask;
use App\Common\Model\TaskCollection;
use App\Common\Model\TaskCollectionInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JsonReader implements ReaderInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function read(string $source): TaskCollectionInterface
    {
        try {
            $tasks = $this->serializer->deserialize($this->getJsonContent($source), InputTask::class . '[]',
                Format::JSON);
        } catch (\Throwable $e) {
            throw new JsonReaderException($e->getMessage(), 0, $e);
        }

        return new TaskCollection($tasks);
    }

    private function getJsonContent(string $source): string
    {
        return file_exists($source) ? file_get_contents($source) : $source;
    }
}
