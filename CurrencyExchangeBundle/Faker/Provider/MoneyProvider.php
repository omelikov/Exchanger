<?php

namespace Exchanger\CurrencyExchangeBundle\Faker\Provider;

use Faker\Provider\Base as BaseProvider;
use Money\Currency;
use Money\Money;

/**
 * Class MoneyProvider
 */
class MoneyProvider extends BaseProvider
{
    /**
     * @param string $currency
     * @param float  $min
     * @param float  $max
     *
     * @return Money
     */
    public function money(string $currency, float $min, float $max): Money
    {
        $number = self::numberBetween($min, $max);

        return new Money($number * 100, new Currency($currency));
    }
}
