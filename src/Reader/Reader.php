<?php

namespace App\Reader;

use App\Common\Model\TaskCollectionInterface;
use App\Reader\Type\ReaderFactoryInterface;
use App\Reader\Type\ReaderInterface;
use App\Tool\Logger;

class Reader
{
    private ReaderFactoryInterface $readerFactory;

    private ReaderInterface $strategy;

    public function __construct(ReaderFactoryInterface $readerFactory)
    {
        $this->readerFactory = $readerFactory;
    }

    public function setType(string $format): Reader
    {
        $this->strategy = $this->readerFactory->create($format);
        return $this;
    }

    public function read(string $source): TaskCollectionInterface
    {
        Logger::use()->log('Read source from ' . $source, ['strategy' => get_class($this->strategy)]);
        return $this->strategy->read($source);
    }
}
