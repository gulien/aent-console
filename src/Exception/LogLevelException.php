<?php
namespace TheAentMachine\Exception;

final class LogLevelException extends AenthillException
{
    public static function invalidLogLevel(string $wrongLogLevel): self
    {
        return new self("Accepted values for log level: DEBUG, INFO, WARN, ERROR. Got '$wrongLogLevel'");
    }

    public static function emptyLogLevel(): self
    {
        return new self('Could not find environment variable PHEROMONE_LOG_LEVEL');
    }
}
