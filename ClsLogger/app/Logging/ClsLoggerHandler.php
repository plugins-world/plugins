<?php

namespace Plugins\ClsLogger\Logging;

use Monolog\LogRecord;
use Monolog\Handler\AbstractProcessingHandler;

class ClsLoggerHandler extends AbstractProcessingHandler
{
    public function write(LogRecord $record): void
    {
        \Plugins\ClsLogger\Utilties\ClsLogUtiltity::logToCls($record['level_name'], $record['formatted']);
    }
}
