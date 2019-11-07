<?php

namespace Exchanger\CurrencyExchangeBundle\EventListener;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Context;
use Money\Money;
use Exchanger\CurrencyExchangeBundle\Formatter\CustomMoneyFormatter;
use Exchanger\CurrencyExchangeBundle\Service\Money\MoneyFormatter;

/**
 * Class MoneySerializerHandler
 */
class MoneySerializerHandler implements SubscribingHandlerInterface
{
    /**
     * @var MoneyFormatter
     */
    protected $moneyFormatter;

    /**
     * MoneySerializerHandler constructor
     *
     * @param MoneyFormatter $moneyFormatter
     */
    public function __construct(MoneyFormatter $moneyFormatter)
    {
        $this->moneyFormatter = $moneyFormatter;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'money',
                'method' => 'serializeMoneyTypeToJson',
            ],
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param Money                    $data
     * @param array                    $type
     * @param Context                  $context
     *
     * @return string
     *
     * @throws \Exception
     */
    public function serializeMoneyTypeToJson(
        JsonSerializationVisitor $visitor,
        Money $data,
        array $type,
        Context $context
    ): string {
        $type = isset($type['params'][0]) ? $type['params'][0] : CustomMoneyFormatter::MONEY;

        return $this->moneyFormatter->format($data, $type);
    }
}
