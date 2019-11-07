<?php

namespace Exchanger\CurrencyExchangeBundle\Service\Money;

use Doctrine\ORM\EntityManager;
use Money\Currencies\CurrencyList;
use Money\Currency;
use Money\Converter;
use Money\Exchange;
use Money\Exchange\IndirectExchange;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Money\Money;
use Exchanger\CurrencyExchangeBundle\Entity\Currency as CurrencyEntity;

/**
 * Class MoneyConverter
 */
class MoneyConverter
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Converter
     */
    protected $moneyConverter;

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
     * @param Money    $money
     * @param Currency $currency
     *
     * @return Money
     */
    public function convert(Money $money, Currency $currency): Money
    {
        return $this->getConverter()->convert($money, $currency);
    }

    /**
     * @return Converter
     */
    protected function getConverter(): Converter
    {
        if (!is_null($this->moneyConverter)) {
            return $this->moneyConverter;
        }

        $this->moneyConverter = new Converter($this->getCurrenciesList(), $this->getExchange());

        return $this->moneyConverter;
    }

    /**
     * @return CurrencyList
     */
    protected function getCurrenciesList(): CurrencyList
    {
        $currencies = $this->getCurrencies();

        $currenciesListData = [];
        foreach ($currencies as $currency) {
            $currenciesListData[$currency->getCode()] = $currency->getPrecision();
        }

        return new CurrencyList($currenciesListData);
    }

    /**
     * @return Exchange
     */
    protected function getExchange(): Exchange
    {
        $exchange = new FixedExchange($this->getExchangeCurrencies());
        $exchange = new ReversedCurrenciesExchange($exchange);
        $exchange = new IndirectExchange($exchange, $this->getCurrenciesList());

        return $exchange;
    }

    /**
     * @return array|CurrencyEntity[]
     */
    protected function getCurrencies(): array
    {
        $currencyRepository = $this->entityManager->getRepository('YouteamCurrencyExchangeBundle:Currency');

        return $currencyRepository->findAll();
    }

    /**
     * @return array
     */
    protected function getExchangeCurrencies(): array
    {
        $currencies = $this->getCurrencies();

        $firstCurrencyCode = '';
        $exchangeCurrencies = [];
        foreach ($currencies as $index => $currency) {
            if (0 === $index) {
                $firstCurrencyCode = $currency->getCode();
            }

            $exchangeCurrencies[$currency->getCode()] = $currency->getRate();
        }

        return [$firstCurrencyCode => $exchangeCurrencies];
    }
}
