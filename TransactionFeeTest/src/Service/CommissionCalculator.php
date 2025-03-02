<?php

namespace Service;

use Entity\Transaction;

class CommissionCalculator
{
    public function __construct(
        private readonly BinCountryService $binCountryService,
        private readonly ExchangeRateService $exchangeRateService
    ) {}

    public function calculate(Transaction $transaction): float
    {
        $amountInEur = $this->convertToEur($transaction);
        return ceil($amountInEur * ($this->binCountryService->isEu($transaction->getBin()) ? 0.01 : 0.02) * 100) / 100;
    }

    private function convertToEur(Transaction $transaction): float
    {
        $rate = $this->exchangeRateService->getExchangeRate($transaction->getCurrency());
        return ($transaction->getCurrency() === 'EUR' || !$rate) ? $transaction->getAmount() : $transaction->getAmount() / $rate;
    }
}