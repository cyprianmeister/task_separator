<?php

namespace App;

use App\Reader\Reader;
use App\Writer\Type\WriterFactory;
use App\Writer\Writer;
use App\Reader\Type\FileReaderFactory;

final class IOFactory
{
    public static function createReader(string $type): Reader
    {
        return (new Reader(new FileReaderFactory()))->setType($type);
    }

    public static function createWriter(string $type): Writer
    {
        return (new Writer(new WriterFactory()))->setType($type);
    }
}
