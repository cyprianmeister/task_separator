<?php

namespace App\Reader\Type;

interface ReaderFactoryInterface
{
    public function create(string $type): ReaderInterface;
}
