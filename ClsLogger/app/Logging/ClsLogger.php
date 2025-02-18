<?php

namespace Plugins\ClsLogger\Logging;

use Illuminate\Log\Logger;
use Illuminate\Support\Arr;
use Psr\Log\LoggerInterface;
use Psr\Log\InvalidArgumentException;

class ClsLogger extends Logger implements LoggerInterface
{
    protected $channelConfig = [];

    public function __invoke(array $config)
    {
        $this->channelConfig = $config;

        return $this;
    }

    public function info($message, array $context = []): void
    {
        $this->log("INFO", $message, $context);
    }

    public function log($level, $message, array $context = []): void
    {
        \Plugins\ClsLogger\Utilties\ClsLogUtiltity::logToCls($level, $message, $context);
    }
}
