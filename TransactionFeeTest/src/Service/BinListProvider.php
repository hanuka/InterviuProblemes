<?php

namespace Service;

use Service\Interface\BinProviderInterface;

class BinListProvider implements BinProviderInterface
{
    private const BIN_API_URL = 'https://lookup.binlist.net/';

    public function getCountryCode(string $bin): ?string
    {
        $url = self::BIN_API_URL . $bin;
        $response = @file_get_contents($url);

        if (!$response) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['country']['alpha2'] ?? null;
    }
}
