<?php

namespace Exchanger\CurrencyExchangeBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class MoneyLabelType
 */
class MoneyLabelType extends AbstractType
{
    /**
     * @return null|string
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'youteam_money_label';
    }
}
