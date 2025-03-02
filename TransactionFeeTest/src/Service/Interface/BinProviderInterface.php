<?php

namespace Service\Interface;

interface BinProviderInterface
{
    /**
     * Fetch the country code (ISO Alpha-2) for a given BIN.
     *
     * @return string|null Returns the country code or null if not found.
     */
    public function getCountryCode(string $bin): ?string;
}