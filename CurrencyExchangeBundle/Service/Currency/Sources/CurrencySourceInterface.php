<?php

namespace Exchanger\CurrencyExchangeBundle\Service\Currency\Sources;

/**
 * Interface CurrencySourceInterface
 */
interface CurrencySourceInterface
{
    /**
     * @return float
     */
    public function getRate(): float;

    /**
     * @return string
     */
    public function getCode(): string;
}
