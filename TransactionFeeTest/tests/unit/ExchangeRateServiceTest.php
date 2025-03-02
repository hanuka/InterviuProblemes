<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use Service\ExchangeRateService;
use Service\Contract\ExchangeRateProviderInterface;

class ExchangeRateServiceTest extends TestCase
{
    private ExchangeRateProviderInterface $exchangeRateProviderMock;
    private ExchangeRateService $exchangeRateService;

    protected function setUp(): void
    {
        $this->exchangeRateProviderMock = $this->createMock(ExchangeRateProviderInterface::class);
        $this->exchangeRateService = new ExchangeRateService($this->exchangeRateProviderMock);
    }

    public function testGetExchangeRateReturnsValidRate(): void
    {
        $this->exchangeRateProviderMock->method('getExchangeRate')->willReturn(1.1);
        $this->assertEquals(1.1, $this->exchangeRateService->getExchangeRate('USD'));
    }

    public function testGetExchangeRateReturnsNullForUnknownCurrency(): void
    {
        $this->exchangeRateProviderMock->method('getExchangeRate')->willReturn(null);
        $this->assertNull($this->exchangeRateService->getExchangeRate('XYZ'));
    }
}