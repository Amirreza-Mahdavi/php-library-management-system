<?php

namespace LMS\CLI;

class Console {

    public function readLine(string $prompt): string {
        echo $prompt;
        return trim(fgets(STDIN));
    }

    public function readInt(string $prompt): int {
        echo $prompt;
        return (int) trim(fgets(STDIN));
    }

    public function writeLine(string $message): void {
        echo $message . PHP_EOL;
    }

    public function success(string $message): void {
        echo "[SUCCESS] " . $message . PHP_EOL;
    }

    public function error(string $message): void {
        echo "[ERROR] " . $message . PHP_EOL;
    }

    public function pause(string $message = "Press Enter to continue..."): void {
        echo PHP_EOL . $message;
        fgets(STDIN);
    }
}