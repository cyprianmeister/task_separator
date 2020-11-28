<?php

namespace App;

use App\Converter\Converter;
use App\Separator\Handler\ConverterHandler;
use App\Separator\Handler\ErrorHandler;
use App\Separator\Handler\HandlerFactory;
use App\Separator\Separator;

final class SeparatorFactory
{
    public static function create(array $targtes, array $taskConverters): Separator
    {
        $chain = [ErrorHandler::class, ConverterHandler::class];
        $targetTypes = array_keys($targtes);
        $handleFactory = new HandlerFactory(new Converter($taskConverters));

        return new Separator($targetTypes, $chain, $handleFactory);
    }
}
