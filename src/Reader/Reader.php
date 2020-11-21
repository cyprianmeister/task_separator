<?php

namespace App\Reader;

use App\Reader\Model\TaskCollectionInterface;
use App\Reader\Type\ReaderFactoryInterface;
use App\Reader\Type\ReaderInterface;

final class Reader
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
        return $this->strategy->read($source);
    }
}
