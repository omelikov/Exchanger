<?php

namespace Exchanger\CurrencyExchangeBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Exchanger\CoreBundle\Admin\AbstractCustomAdmin;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;

/**
 * Class CurrencyAdmin
 */
class CurrencyAdmin extends AbstractCustomAdmin
{
    /**
     * @var string
     */
    protected $baseRouteName = 'admin_youteam_currency_exchange_currency';

    /**
     * @var string
     */
    protected $baseRoutePattern = 'exchanger/currencyexchange/currency';

    /**
     * CurrencyAdmin constructor
     *
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        // Set default sort order
        if (!$this->hasRequest()) {
            $this->datagridValues['_sort_order'] = 'DESC';
            $this->datagridValues['_sort_by'] = 'createdAt';
        }
    }

    /**
     * @param Currency $object
     *
     * @return string
     */
    public function toString($object)
    {
        if (!is_null($object->getId())) {
            return $object->getName();
        }

        return 'Currency';
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);

        $collection->remove('create');
        $collection->remove('edit');
        $collection->remove('show');
        $collection->remove('delete');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('code')
            ->add('name')
            ->add('prefix')
            ->add('rate')
            ->add('suffix')
            ->add('precision')
            ->add('updatedAt', 'datetime', [
                'label' => 'Updated',
                'format' => 'd/m/Y H:i e',
                'template' => 'YouteamCoreBundle:Admin:CRUD/date_value.html.twig',
            ]);
    }
}
