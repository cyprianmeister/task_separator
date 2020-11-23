<?php

namespace App\Writer\Type;

interface WriterFactoryInterface
{
    public function create(string $type): WriterInterface;
}
