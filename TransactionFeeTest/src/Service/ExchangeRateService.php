<?php

namespace Service;

use Service\Interface\ExchangeRateProviderInterface;

class ExchangeRateService
{
    private ExchangeRateProviderInterface $exchangeRateProvider;

    public function __construct(ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->exchangeRateProvider = $exchangeRateProvider;
    }

    public function getExchangeRate(string $currency): ?float
    {
        return $this->exchangeRateProvider->getExchangeRate($currency);
    }
}