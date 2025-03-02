<?php

namespace Repository;

use Entity\Transaction;

class FileReader
{
    public function __construct(private readonly string $filePath) {}

    public function readTransactions(): array
    {
        if (!is_readable($this->filePath)) {
            return [];
        }

        return array_filter(array_map(fn($line) => $this->parseTransaction($line),
            file($this->filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)));
    }

    private function parseTransaction(string $line): ?Transaction
    {
        $data = json_decode($line, true);
        return isset($data['bin'], $data['amount'], $data['currency'])
            ? new Transaction($data['bin'], (float) $data['amount'], $data['currency'])
            : null;
    }
}