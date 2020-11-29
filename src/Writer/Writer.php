<?php

namespace App\Writer;

use App\Common\Model\TaskCollectionInterface;
use App\Tool\Logger;
use App\Writer\Type\WriterFactoryInterface;
use App\Writer\Type\WriterInterface;

class Writer
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
        Logger::use()->log('Write result to ' . $target, ['strategy' => get_class($this->strategy)]);
        $this->strategy->setTarget($target)->write($taskCollection);
    }
}
