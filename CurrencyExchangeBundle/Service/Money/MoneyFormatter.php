<?php

namespace Exchanger\CurrencyExchangeBundle\Service\Money;

use Doctrine\ORM\EntityManager;
use Money\Money;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;
use Exchanger\CurrencyExchangeBundle\Formatter\CustomMoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Exception\MoneyTypeNotSupportedException;

/**
 * Class MoneyFormatter
 */
class MoneyFormatter
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var CustomMoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * MoneyFormatter constructor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Money  $money
     * @param string $type
     *
     * @return string
     *
     * @throws MoneyTypeNotSupportedException
     */
    public function format(Money $money, string $type = CustomMoneyFormatter::DEFAULT): string
    {
        $formatter = $this->getMoneyFormatter();

        return $formatter->format($money, $type);
    }

    /**
     * @return CustomMoneyFormatter
     */
    protected function getMoneyFormatter(): CustomMoneyFormatter
    {
        if (!is_null($this->moneyFormatter)) {
            return $this->moneyFormatter;
        }

        $this->moneyFormatter = new CustomMoneyFormatter($this->getCurrencies());

        return $this->moneyFormatter;
    }

    /**
     * @return array|Currency[]
     */
    protected function getCurrencies(): array
    {
        $currencyRepository = $this->entityManager->getRepository('YouteamCurrencyExchangeBundle:Currency');

        return $currencyRepository->findAll();
    }
}
