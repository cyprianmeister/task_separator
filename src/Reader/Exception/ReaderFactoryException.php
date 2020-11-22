<?php

namespace App\Reader\Exception;

use Throwable;

final class ReaderFactoryException extends \Exception
{
    public function __construct(string $type)
    {
        $message = 'Can\'t create Reader of type ' . $type;
        parent::__construct($message);
    }
}
