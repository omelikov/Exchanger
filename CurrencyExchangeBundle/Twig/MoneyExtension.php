<?php

namespace Exchanger\CurrencyExchangeBundle\Twig;

use Money\Money;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Exchanger\CurrencyExchangeBundle\Exception\MoneyTypeNotSupportedException;
use Exchanger\CurrencyExchangeBundle\Formatter\CustomMoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Service\Money\MoneyFormatter;

/**
 * Class MoneyExtension
 */
class MoneyExtension extends AbstractExtension
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * MoneyExtension constructor
     *
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('money_format', [$this, 'moneyFormatFilter']),
        ];
    }

    /**
     * @param Money  $money
     * @param string $format
     *
     * @return string
     *
     * @throws MoneyTypeNotSupportedException
     */
    public function moneyFormatFilter(Money $money, string $format = CustomMoneyFormatter::MONEY): string
    {
        return $this->moneyFormatter->format($money, $format);
    }
}
