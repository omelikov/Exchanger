<?php

namespace Exchanger\CurrencyExchangeBundle\Exception;

use Exception;
use Throwable;

/**
 * Class CurrencyNotAllowedException
 */
class CurrencyNotAllowedException extends Exception
{
    private const MESSAGE_PATTER = 'Allowed only those currencies %s';

    /**
     * CurrencyNotAllowedException constructor
     *
     * @param array          $currencies
     * @param string         $messagePattern
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        array $currencies,
        string $messagePattern = self::MESSAGE_PATTER,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($this->generateMessage($currencies, $messagePattern), $code, $previous);
    }

    /**
     * @param array  $currencies
     * @param string $messagePattern
     *
     * @return string
     */
    private function generateMessage(array $currencies, string $messagePattern): string
    {
        return sprintf($messagePattern, implode(', ', $currencies));
    }
}
