<?php
namespace TheAentMachine;

use Symfony\Component\Process\Process;

class Hercule
{
    const BINARY = 'hercule';

    /**
     * @param string[] $events
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     */
    public static function setHandledEvents(array $events): void
    {
        $command = Hercule::BINARY . ' set:handled-events';
        foreach ($events as $event) {
            $command .= " $event";
        }

        $process = new Process($command);
        $process->enableOutput();
        $process->setTty(true);

        $process->mustRun();
    }
}