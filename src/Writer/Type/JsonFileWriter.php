<?php

namespace App\Writer\Type;

use App\Common\Model\Enum\Format;
use App\Common\Model\TaskCollectionInterface;
use App\Writer\Exception\JsonFileWriterException;
use App\Writer\Exception\JsonFileWriterTargetException;
use Symfony\Component\Serializer\SerializerInterface;

class JsonFileWriter implements WriterInterface
{
    private SerializerInterface $serializer;

    private string $target;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function setTarget($target): WriterInterface
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @param TaskCollectionInterface $taskCollection
     * @throws JsonFileWriterException
     * @throws JsonFileWriterTargetException
     */
    public function write(TaskCollectionInterface $taskCollection): void
    {
        try {
            $jsonContent = $this->serializer->serialize($taskCollection->toArray(), Format::JSON, ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
        } catch (\Throwable $e) {
            throw new JsonFileWriterException('Task collection can\'t be serialized. ' . $e->getMessage());
        }

        $this->save($jsonContent);
    }

    /**
     * @param string $jsonContent
     * @throws JsonFileWriterException
     * @throws JsonFileWriterTargetException
     */
    private function save(string $jsonContent): void
    {
        if (!$this->target) {
            throw new JsonFileWriterTargetException();
        }

        if (!file_put_contents($this->target, $jsonContent)) {
            throw new JsonFileWriterException('Can\'t save JSON content to file ' . $this->target . '.');
        }
    }
}
