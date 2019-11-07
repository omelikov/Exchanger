<?php

namespace Exchanger\CurrencyExchangeBundle\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Money\Money;
use Money\Currency;

/**
 * Class MoneyType
 */
class MoneyType extends Type
{
    const MONEY = 'money';

    /**
     * @return string
     */
    public function getName()
    {
        return self::MONEY;
    }

    /**
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getBigIntTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @param int|null         $value
     * @param AbstractPlatform $platform
     *
     * @return Money|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new \InvalidArgumentException('Argument $value must be integer or string');
        }

        return new Money($value, new Currency(''));
    }

    /**
     * @param Money|null       $value
     * @param AbstractPlatform $platform
     *
     * @return int|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return $value;
        }

        if (!($value instanceof Money)) {
            throw new \InvalidArgumentException(sprintf('Argument $value must instance of %s', Money::class));
        }

        return $value->getAmount();
    }

    /**
     * @return bool
     */
    public function canRequireSQLConversion()
    {
        return true;
    }
}
