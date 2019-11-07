<?php

namespace Exchanger\CurrencyExchangeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\LockableTrait;

/**
 * Class UpdateCurrenciesCommand
 */
class UpdateCurrenciesCommand extends ContainerAwareCommand
{
    use LockableTrait;

    /**
     * Configure
     */
    protected function configure()
    {
        $this
            ->setName('exchanger:currency:update')
            ->setDescription('Update currencies')
            ->setHelp('API get currency from https://coinmarketcap.com/api/');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        $container = $this->getContainer();

        $container->get('exchanger.currency_exchange_bundle.currency_rates_updater')->update();

        $output->writeln('Currency rates successfully updated');

        return 0;
    }
}
