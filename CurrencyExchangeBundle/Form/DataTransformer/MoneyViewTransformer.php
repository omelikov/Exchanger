<?php

namespace Exchanger\CurrencyExchangeBundle\Form\DataTransformer;

use Money\Currency;
use Money\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Exchanger\CurrencyExchangeBundle\Formatter\CustomMoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Service\Money\MoneyFormatter;

/**
 * Class MoneyViewTransformer
 */
class MoneyViewTransformer implements DataTransformerInterface
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * MoneyViewTransformer constructor
     *
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * From Norm to View
     *
     * @param Money|null $value
     *
     * @return mixed|null|string
     *
     * @throws \Exception
     */
    public function transform($value)
    {
        if (is_null($value)) {
            return null;
        }

        list($value, $currency) = explode(' ', $value);

        // TODO get crypto currencies from database
        $cryptoCurrencies = ['BTC', 'ETH', 'XRP', 'LTC', 'ZEC'];

        if (in_array($currency, $cryptoCurrencies)) {
            $value = (float) $value * 100000000;
        } elseif ('' === $currency) {
            // TODO костиль на випадок якщо якимось чином currency буде порожнім
            $value = (float) $value * 100000000;
        } else {
            $value = (float) $value * 100;
        }

        return $this->moneyFormatter->format(
            new Money((int) round($value, 0), new Currency($currency)),
            CustomMoneyFormatter::SIMPLE_NUMBER
        );
    }

    /**
     * From View to Norm
     *
     * @param mixed $value
     *
     * @return mixed|null|string
     */
    public function reverseTransform($value)
    {
        if (is_null($value)) {
            return null;
        }

        // цей метод використовується коли форма не пройшла валідацію
        // TODO пошукати спосіб встановити правильне значення валюти
        $currency = '';
        if (mb_stripos($value, ' ') !== false) {
            list($value, $currency) = explode(' ', $value);
        }

        return $value.' '.$currency;
    }
}
