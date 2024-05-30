<?php

namespace cli;
class Cli
{
    public function __construct()
    {
//        readline_completion_function([$this, 'completion']);
    }


    public final function ask(string $question): string
    {
        $result = readline($question);
        return trim($result);
    }

    public final function space(int $number = 1): void
    {
        for ($i = 0; $i < $number; $i++) {
            echo PHP_EOL;
        }
    }

    public final function completion(string $input, int $index): array
    {
        $commands = ['controller', 'entity'];
        return array_filter($commands, function ($command) use ($input) {
            return strpos($command, $input) === 0;
        });
    }

    public final function askChoice(string $question, array $choices): string
    {
        $menu = array_map(function ($choice) {
            static $index = 0;
            $index++;
            return '[' . $index . "]" . " - " . $choice;
        }, $choices);
        $menu = implode(PHP_EOL, $menu);
        $this->write($question . PHP_EOL . $menu . PHP_EOL);
        $choice = $this->ask("Votre choix : ");
        if (is_numeric($choice) && $choice > count($choices)) {
            $this->writeError("Choix invalide\n");
            return $this->askChoice($question, $choices);
        }
        return $choices[(int)$choice - 1];
    }


    public final function write(string $message): void
    {
        echo $message;
    }

    public final function writeln(string $message): void
    {
        echo $message . "\n";
    }

    public final function checkInput(string $input): bool
    {
        return !empty($input);
    }

    public final function checkEntryType(string $type): bool
    {
        return in_array($type, ['int', 'string', 'float', 'bool', 'array', 'null']);
    }


    public final function writeError(string $message): void
    {
        fwrite(STDERR, $message);
    }

    public final function clearScreen(): void
    {
        echo "\033[2J\033[;H";
    }

}