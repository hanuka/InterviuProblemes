<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use Service\CommissionCalculator;
use Service\BinCountryService;
use Service\ExchangeRateService;
use Entity\Transaction;

class CommissionCalculatorTest extends TestCase
{
    private BinCountryService $binCountryServiceMock;
    private ExchangeRateService $exchangeRateServiceMock;
    private CommissionCalculator $commissionCalculator;

    protected function setUp(): void
    {
        $this->binCountryServiceMock = $this->createMock(BinCountryService::class);
        $this->exchangeRateServiceMock = $this->createMock(ExchangeRateService::class);
        $this->commissionCalculator = new CommissionCalculator(
            $this->binCountryServiceMock,
            $this->exchangeRateServiceMock
        );
    }

    public function testCalculateForEUCountry(): void
    {
        $this->binCountryServiceMock->method('isEu')->willReturn(true);
        $this->exchangeRateServiceMock->method('getExchangeRate')->willReturn(1.0);

        $transaction = new Transaction('45717360', 100.00, 'EUR');
        $this->assertEquals(1.00, $this->commissionCalculator->calculate($transaction));
    }

    public function testCalculateForNonEUCountry(): void
    {
        $this->binCountryServiceMock->method('isEu')->willReturn(false);
        $this->exchangeRateServiceMock->method('getExchangeRate')->willReturn(1.0);

        $transaction = new Transaction('516793', 50.00, 'USD');
        $this->assertEquals(1.00, $this->commissionCalculator->calculate($transaction));
    }

    public function testCalculateWithCurrencyConversion(): void
    {
        $this->binCountryServiceMock->method('isEu')->willReturn(false);
        $this->exchangeRateServiceMock->method('getExchangeRate')->willReturn(2.0);

        $transaction = new Transaction('41417360', 130.00, 'USD');
        $this->assertEquals(1.30, $this->commissionCalculator->calculate($transaction));
    }
}