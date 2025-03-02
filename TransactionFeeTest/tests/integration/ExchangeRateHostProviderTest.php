<?php

namespace Tests\integration;

use PHPUnit\Framework\TestCase;
use Service\ExchangeRateHostProvider;

class ExchangeRateHostProviderTest extends TestCase
{
    private ExchangeRateHostProvider $exchangeRateHostProvider;

    protected function setUp(): void
    {
        $apiKey = 'ZDlDi5ITrmPIVKLfCNRAgZkkIpD93IWp';
        $this->exchangeRateHostProvider = new ExchangeRateHostProvider($apiKey);
    }

    public function testGetExchangeRateReturnsValidRate(): void
    {
        $rate = $this->exchangeRateHostProvider->getExchangeRate('USD');

        $this->assertIsFloat($rate);
        $this->assertGreaterThan(0, $rate);
    }

    public function testGetExchangeRateReturnsNullForInvalidCurrency(): void
    {
        $rate = $this->exchangeRateHostProvider->getExchangeRate('XYZ');

        $this->assertNull($rate);
    }
}