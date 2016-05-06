<?php

namespace Springbot\Queue\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;

/**
 * Class ProcessQueue
 *
 * @package Springbot\Queue\Console\Command
 */
class ProcessQueue extends Command
{

    /**
     * ProcessQueue constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        parent::__construct();
    }

    /**
     * Sets config for cli command
     */
    protected function configure()
    {
        $this->setName('springbot:queue:process')
            ->setDescription('Process Queue');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $output->writeln("Queue Processed.");
        } catch (\Exception $e) {
            $output->writeln("Could not do the thing: " . $e->getMessage());
        }
    }
}
