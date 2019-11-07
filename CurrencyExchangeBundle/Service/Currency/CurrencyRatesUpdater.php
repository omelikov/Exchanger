<?php

namespace Exchanger\CurrencyExchangeBundle\Service\Currency;

use Doctrine\ORM\EntityManager;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;
use Exchanger\CurrencyExchangeBundle\Service\Currency\Sources\CurrencySourceInterface;
use Exchanger\CurrencyExchangeBundle\Entity\CurrencyRate;

/**
 * Class CurrencyRatesUpdater
 */
class CurrencyRatesUpdater
{
    /**
     * @var EntityManager
     */
    protected $doctrine;

    /**
     * @var CurrencySourceInterface[]
     */
    protected $currencySources = [];

    /**
     * CurrencyRatesUpdater constructor
     *
     * @param EntityManager $doctrine
     */
    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param CurrencySourceInterface $currencySource
     *
     * @throws \Exception
     */
    public function addCurrencySource(CurrencySourceInterface $currencySource)
    {
        if (isset($this->currencySources[get_class($currencySource)])) {
            throw new \Exception('The currency source has already been added.');
        }

        $this->currencySources[get_class($currencySource)] = $currencySource;
    }

    /**
     * @return void
     *
     * @throws \Exception
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(): void
    {
        $rates = [];
        foreach ($this->currencySources as $currencySource) {
            $rates[$currencySource->getCode()] = $currencySource->getRate();
        }

        if (empty($rates)) {
            throw new \Exception('Currency sources is not added');
        }

        $currencyRepository = $this->doctrine->getRepository('YouteamCurrencyExchangeBundle:Currency');

        foreach ($rates as $code => $value) {
            /** @var Currency $currency */
            $currency = $currencyRepository->findOneByCode($code);
            if (is_null($currency)) {
                throw new \Exception('Currencies not found, check API.');
            }

            $currency->setRate($value);

            $currencyRate = new CurrencyRate();
            $currencyRate->setRate($value);
            $currencyRate->setCurrency($currency);

            $this->doctrine->persist($currencyRate);
        }

        $this->doctrine->flush();
    }
}
