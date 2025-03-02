<?php

namespace Service\Interface;

interface ExchangeRateProviderInterface
{
    /**
     * Fetch the exchange rate for a given currency.
     *
     * @return float|null Returns the exchange rate or null if not found.
     */
    public function getExchangeRate(string $currency): ?float;
}