<?php

namespace Exchanger\CurrencyExchangeBundle\Formatter;

use Money\Money;
use Money\Number;
use Money\MoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;
use Exchanger\CurrencyExchangeBundle\Exception\MoneyTypeNotSupportedException;

/**
 * Class CustomMoneyFormatter
 */
class CustomMoneyFormatter implements MoneyFormatter
{
    const DEFAULT = self::MONEY;

    /**
     * new Money(123456, new Currency('USD')) => 123456
     */
    const COIN = 'coin';

    /**
     * new Money(123456, new Currency('USD')) => 1234.56
     */
    const SIMPLE_NUMBER = 'simple_number';

    /**
     * new Money(123456, new Currency('USD')) => 1,234.56
     */
    const NUMBER = 'number';

    /**
     * new Money(123456, new Currency('USD')) => $1,234.56
     */
    const MONEY = 'money';

    /**
     * @var array|Currency[]
     */
    private $currencies;

    /**
     * @var array
     */
    private $currenciesIndexes;

    /**
     * CustomMoneyFormatter constructor
     *
     * @param array|Currency[] $currencies
     */
    public function __construct(array $currencies)
    {
        $this->currencies = $currencies;

        $this->initCurrenciesIndexes();
    }

    /**
     * @param Money  $money
     * @param string $type
     *
     * @return bool|string
     *
     * @throws MoneyTypeNotSupportedException
     */
    public function format(Money $money, string $type = self::DEFAULT)
    {
        $currency = $this->getCurrency($money->getCurrency());
        $valueBase = $money->getAmount();
        $negative = false;

        if ('-' === $valueBase[0]) {
            $negative = true;
            $valueBase = substr($valueBase, 1);
        }

        $subunit = $currency->getPrecision();
        $fractionDigits = $subunit;
        $valueBase = Number::roundMoneyValue($valueBase, $fractionDigits, $subunit);
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted = substr($valueBase, 0, $valueLength - $subunit);

            if ($subunit) {
                $formatted .= '.';
                $formatted .= substr($valueBase, $valueLength - $subunit);
            }
        } else {
            $formatted = '0.'.str_pad('', $subunit - $valueLength, '0').$valueBase;
        }

        if (true === $negative) {
            $formatted = '-'.$formatted;
        }

        if (self::COIN === $type) {
            $formatted = (string) number_format((float) $formatted, $fractionDigits, '', '');
        } elseif (self::SIMPLE_NUMBER === $type) {
            $formatted = (string) number_format((float) $formatted, $fractionDigits, '.', '');
        } elseif (self::NUMBER === $type) {
            $formatted = (string) number_format((float) $formatted, $fractionDigits, '.', ',');
        } elseif (self::MONEY === $type) {
            $formatted = (string) number_format((float) $formatted, $fractionDigits, '.', ',');
            $formatted = $currency->getPrefix().$formatted.$currency->getSuffix();
        } else {
            throw new MoneyTypeNotSupportedException("Type \"{$type}\" is not supported");
        }

        return $formatted;
    }

    /**
     * Init currencies indexes for search
     *
     * @return void
     */
    protected function initCurrenciesIndexes(): void
    {
        $this->currenciesIndexes = [];
        foreach ($this->currencies as $index => $currency) {
            $this->currenciesIndexes[$currency->getCode()] = $index;
        }
    }

    /**
     * @param string $code
     *
     * @return Currency
     */
    protected function getCurrency(string $code): Currency
    {
        if (!isset($this->currenciesIndexes[$code])) {
            // TODO need fix form with empty currency (Add new Item)
            $code = 'USD';
            // throw new UnknownCurrencyException("Cannot find currency \"{$code}\"");
        }

        return $this->currencies[$this->currenciesIndexes[$code]];
    }
}
