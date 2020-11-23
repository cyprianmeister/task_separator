<?php

namespace App\Writer;

use App\Common\Model\TaskCollectionInterface;
use App\Writer\Type\WriterFactoryInterface;
use App\Writer\Type\WriterInterface;

final class Writer
{
    private WriterFactoryInterface $writerFactory;

    private WriterInterface $strategy;

    public function __construct(WriterFactoryInterface $writerFactory)
    {
        $this->writerFactory = $writerFactory;
    }

    public function setType(string $format): Writer
    {
        $this->strategy = $this->writerFactory->create($format);
        return $this;
    }

    public function write(TaskCollectionInterface $taskCollection, string $target): void
    {

        $this->strategy->setTarget($target)->write($taskCollection);
    }
}
