# Transaction Fee Calculator

## 📌 Description
This project calculates transaction fees based on the card's BIN and currency used.
Different commission rates apply: **1% for EU transactions** and **2% for non-EU transactions**.
All fees are calculated in EUR.

## 🚀 Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/username/transaction-fee-calculator.git
   cd transaction-fee-calculator
   ```
2. Install dependencies:
   ```sh
   composer install
   ```

## 🔥 Usage
1. Add transactions to `input.txt`, each on a new line:
   ```json
   {"bin":"45717360","amount":"100.00","currency":"EUR"}
   {"bin":"516793","amount":"50.00","currency":"USD"}
   ```
2. Run the application:
   ```sh
   php bin/app.php
   ```
3. Example output:
   ```
   1.00
   0.47
   ```

## 🧪 Testing
To run unit tests:
```sh
composer test
```

## 🛠️ Technologies Used
- PHP 8.1+
- Composer for dependency management
- PHPUnit for testing
- External APIs: [binlist.net](https://lookup.binlist.net/) and [exchangerate.host](https://api.exchangerate.host/latest)


## 📄 License
This project is licensed under the **MIT License**.
