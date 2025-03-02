<?php

namespace Service;

use Service\Interface\ExchangeRateProviderInterface;

class ExchangeRateHostProvider implements ExchangeRateProviderInterface
{
    private const API_URL = 'https://api.apilayer.com/exchangerates_data/latest';
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getExchangeRate(string $currency): ?float
    {
        $url = self::API_URL . "?base=EUR&symbols=" . $currency;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: " . $this->apiKey
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['rates'][$currency] ?? null;
    }
}
