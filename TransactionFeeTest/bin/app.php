<?php

require __DIR__ . '/../vendor/autoload.php';

use Repository\FileReader;
use Service\BinCountryService;
use Service\BinListProvider;
use Service\ExchangeRateService;
use Service\ExchangeRateHostProvider;
use Service\CommissionCalculator;

$filePath = __DIR__ . '/../input.txt';
$fileReader = new FileReader($filePath);
$apiKey = 'ZDlDi5ITrmPIVKLfCNRAgZkkIpD93IWp';

$binProvider = new BinListProvider();
$binService = new BinCountryService($binProvider);

$exchangeProvider = new ExchangeRateHostProvider($apiKey);
$exchangeService = new ExchangeRateService($exchangeProvider);

$commissionCalculator = new CommissionCalculator($binService, $exchangeService);

$transactions = $fileReader->readTransactions();

foreach ($transactions as $transaction) {
    $commission = $commissionCalculator->calculate($transaction);
    echo number_format($commission, 2) . PHP_EOL;
}
