<?php

namespace Exchanger\CurrencyExchangeBundle\DataFixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Exchanger\CoreBundle\DataFixtures\AbstractYouteamFixture;

/**
 * Class CurrencyFixtures
 */
class CurrencyFixtures extends AbstractYouteamFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadFixtureFiles($manager, [
            '@YouteamCurrencyExchangeBundle/Resources/fixtures/currency.yml',
        ]);
    }
}
