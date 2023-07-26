<?php

namespace JSmart\Log;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    public function log($level, $message, array $context = []): void
    {
        //dump($level, $message, $context, '---------------------------------');
    }

    /**
     * Write a message to the log.
     *
     * @param string $level
     * @param mixed $message
     * @param array $context
     * @return void
     */
    protected function writeLog(string $level, mixed $message, array $context): void
    {
        $this->logger->{$level}(
            $message = $this->formatMessage($message),
            $context = array_merge($this->context, $context)
        );

        $this->fireLogEvent($level, $message, $context);
    }
}
