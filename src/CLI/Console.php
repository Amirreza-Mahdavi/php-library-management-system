<?php

namespace LMS\CLI;

class Console {
    public function readInput(string $prompt): string {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    public function readInt(int $prompt): int {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    public function wruteLine(string $message): void {
        echo $message . PHP_EOL;
    }

    public function success(string $message): void {
        echo "[SUCCESS] " . $message . PHP_EOL;
    }

    public function error(string $message): void {
        echo "[ERROR] " . $message . PHP_EOL;
    }
    

}