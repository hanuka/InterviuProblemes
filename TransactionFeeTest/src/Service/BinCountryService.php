<?php

namespace Service;

use Service\Interface\BinProviderInterface;

class BinCountryService
{
    private const EU_COUNTRIES = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR',
        'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO',
        'SE', 'SI', 'SK'
    ];

    public function __construct(private readonly BinProviderInterface $binProvider) {}

    public function isEu(string $bin): bool
    {
        return in_array($this->binProvider->getCountryCode($bin), self::EU_COUNTRIES, true);
    }
}
