<?php

namespace Thor\Process;

class CommandError extends \RuntimeException
{

    public static function notFound(string $input): self
    {
        return new self("Command \"$input\" not found");
    }

    public static function mismatch(Command $cliCommand, string $input): self
    {
        return new self("The command line \"$input\" mismatches the \"{$cliCommand->command}\" command.");
    }

    public static function misusage(Command $cliCommand): self
    {
        $cliCommand->usage();
        return new self("Invalid usage of \"{$cliCommand->command}\"");
    }

}
