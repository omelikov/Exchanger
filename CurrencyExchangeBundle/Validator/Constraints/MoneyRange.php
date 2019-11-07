<?php

namespace Exchanger\CurrencyExchangeBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Range;

/**
 * @Annotation
 *
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class MoneyRange extends Range
{
    public $moneyTypeMessage = 'Variable $value must instance of \\Money\\Money';
}
