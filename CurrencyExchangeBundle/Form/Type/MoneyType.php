<?php

namespace Exchanger\CurrencyExchangeBundle\Form\Type;

use Money\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exchanger\CurrencyExchangeBundle\Form\DataTransformer\MoneyModelTransformer;
use Exchanger\CurrencyExchangeBundle\Form\DataTransformer\MoneyTransformer;
use Exchanger\CurrencyExchangeBundle\Form\DataTransformer\MoneyViewTransformer;

/**
 * Class MoneyType
 */
class MoneyType extends AbstractType
{
    /**
     * @var MoneyModelTransformer
     */
    protected $modelTransformer;

    /**
     * @var MoneyViewTransformer
     */
    protected $viewTransformer;

    /**
     * MoneyType constructor
     *
     * @param MoneyModelTransformer $modelTransformer
     * @param MoneyViewTransformer  $viewTransformer
     */
    public function __construct(MoneyModelTransformer $modelTransformer, MoneyViewTransformer $viewTransformer)
    {
        $this->modelTransformer = $modelTransformer;
        $this->viewTransformer = $viewTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired('get_currency_code');
        $resolver->setDefault('money_formatter', null);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->modelTransformer);
        $builder->addViewTransformer($this->viewTransformer);

        $preSubmitCallback = function (FormEvent $event) use ($options) {
            /** @var Money $money */
            $data = $event->getData();

            if (is_null($data)) {
                return null;
            }

            $currency = $options['get_currency_code']($event);
            $data = $event->getData().' '.$currency;

            $event->setData($data);
        };

        $builder->addEventListener(FormEvents::PRE_SUBMIT, $preSubmitCallback);
    }

    /**
     * {@inheritdoc}
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
        return 'youteam_money';
    }
}
