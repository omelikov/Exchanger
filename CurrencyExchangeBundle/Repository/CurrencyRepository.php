<?php

namespace Exchanger\CurrencyExchangeBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;

/**
 * Class CurrencyRepository
 */
class CurrencyRepository extends EntityRepository
{
    /**
     * @param string $code
     *
     * @return Currency
     */
    public function findOneByCode(string $code): Currency
    {
        return $this->findOneBy(['code' => $code]);
    }
}
