<?php

namespace Exchanger\CurrencyExchangeBundle\Form\DataTransformer;

use Money\Currency;
use Money\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Exchanger\CurrencyExchangeBundle\Formatter\CustomMoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Service\Money\MoneyFormatter;

/**
 * Class MoneyModelTransformer
 */
class MoneyModelTransformer implements DataTransformerInterface
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * MoneyModelTransformer constructor
     *
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * From Model to Norm
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

        return $this->moneyFormatter->format($value, CustomMoneyFormatter::SIMPLE_NUMBER).' '.$value->getCurrency()->getCode();
    }

    /**
     * From Norm to Model
     *
     * @param string|null $value
     *
     * @return null|Money
     */
    public function reverseTransform($value)
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

        return new Money((int) round($value, 0), new Currency($currency));
    }
}
