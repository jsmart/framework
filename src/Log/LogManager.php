<?php

namespace JSmart\Log;

use Monolog\Logger as Monolog;

class LogManager
{
    protected array $levels = [
        'debug'     => Monolog::DEBUG,
        'info'      => Monolog::INFO,
        'notice'    => Monolog::NOTICE,
        'warning'   => Monolog::WARNING,
        'error'     => Monolog::ERROR,
        'critical'  => Monolog::CRITICAL,
        'alert'     => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    public function __construct()
    {
        //
    }

    /**
     * Create an instance of the single file log driver.
     *
     * @param array $config
     * @return Monolog|LoggerInterface
     */
    protected function createSingleDriver(array $config): Monolog|LoggerInterface
    {
        return new Monolog('local', [
            new \Monolog\Handler\StreamHandler($config['path'], $this->level($config))
        ]);
    }

    /**
     * Create an instance of the daily file log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createDailyDriver(array $config)
    {
        //
    }

    /**
     * Create an instance of the syslog log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createSyslogDriver(array $config)
    {
        //
    }

    /**
     * Create an instance of the "error log" log driver.
     *
     * @param array $config
     * @return LoggerInterface
     */
    protected function createErrorlogDriver(array $config)
    {
        //
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array  $config
     * @return int
     * @throws InvalidArgumentException
     */
    protected function level(array $config): int
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new \InvalidArgumentException('Invalid log level.');
    }
}
