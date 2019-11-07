<?php

namespace Exchanger\CurrencyExchangeBundle\Validator\Constraints;

use Money\Money;
use Symfony\Component\Validator\Constraints\RangeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class MoneyRangeValidator
 */
class MoneyRangeValidator extends RangeValidator
{
    /**
     * @param Money|null            $value
     * @param Constraint|MoneyRange $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!is_null($value) && !($value instanceof Money)) {
            $this->context->buildViolation($constraint->moneyTypeMessage)->addViolation();

            return;
        }

        // TODO get crypto currencies from database
        $cryptoCurrencies = ['BTC', 'ETH', 'XRP', 'LTC', 'ZEC'];

        // TODO при створенні item метод $value->getCurrency() вертає значення entity, а не з форми
        if (in_array($value->getCurrency()->getCode(), $cryptoCurrencies)) {
            $value = (int) ($value->getAmount() / 100000000);
        } elseif ($value->getCurrency()->getCode() === '') {
            // TODO костиль на випадок якщо якимось чином currency буде порожнім
            $value = (int) ($value->getAmount() / 100000000);
        } else {
            $value = (int) ($value->getAmount() / 100);
        }

        parent::validate($value, $constraint);
    }
}
