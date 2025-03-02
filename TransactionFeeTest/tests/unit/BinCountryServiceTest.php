<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use Service\BinCountryService;
use Service\Contract\BinProviderInterface;

class BinCountryServiceTest extends TestCase
{
    private BinProviderInterface $binProviderMock;
    private BinCountryService $binCountryService;

    protected function setUp(): void
    {
        $this->binProviderMock = $this->createMock(BinProviderInterface::class);
        $this->binCountryService = new BinCountryService($this->binProviderMock);
    }

    public function testIsEuReturnsTrueForEUCountries(): void
    {
        $this->binProviderMock->method('getCountryCode')->willReturn('FR');
        $this->assertTrue($this->binCountryService->isEu('45717360'));
    }

    public function testIsEuReturnsFalseForNonEUCountries(): void
    {
        $this->binProviderMock->method('getCountryCode')->willReturn('US');
        $this->assertFalse($this->binCountryService->isEu('516793'));
    }

    public function testIsEuReturnsFalseForNullCountry(): void
    {
        $this->binProviderMock->method('getCountryCode')->willReturn(null);
        $this->assertFalse($this->binCountryService->isEu('000000'));
    }
}
