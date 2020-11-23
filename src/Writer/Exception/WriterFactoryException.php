<?php

namespace App\Writer\Exception;

final class WriterFactoryException extends \Exception
{
    public function __construct(string $type)
    {
        $message = 'Can\'t create writer of type ' . $type;
        parent::__construct($message);
    }
}
