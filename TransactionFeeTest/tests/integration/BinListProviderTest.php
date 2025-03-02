<?php

namespace Tests\integration;

use PHPUnit\Framework\TestCase;
use Service\BinListProvider;

class BinListProviderTest extends TestCase
{
    private BinListProvider $binListProvider;

    protected function setUp(): void
    {
        $this->binListProvider = new BinListProvider();
    }

    public function testGetCountryCodeReturnsValidCountry(): void
    {
        $bin = '45717360';
        $countryCode = $this->binListProvider->getCountryCode($bin);

        $this->assertIsString($countryCode);
        $this->assertEquals(2, strlen($countryCode));
    }

    public function testGetCountryCodeReturnsNullForInvalidBin(): void
    {
        $bin = '00000000';
        $countryCode = $this->binListProvider->getCountryCode($bin);

        $this->assertNull($countryCode);
    }
}