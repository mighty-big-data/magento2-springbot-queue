<?php

namespace Springbot\Queue\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\State;
use Symfony\Component\Console\Input\InputArgument;
use Springbot\Queue\Model\Queue;

/**
 * Class EnqueueCommand
 *
 * @package Springbot\Queue\Console\Command
 */
class EnqueueCommand extends Command
{

    const CLASS_ARGUMENT = '<class>';
    const METHOD_ARGUMENT = '<method>';
    const PRIORITY_ARGUMENT = '<priority>';
    const QUEUE_ARGUMENT = '<queue>';
    const PARAMS_ARGUMENT = '<params>';

    private $_queue;

    /**
     * @param State $state
     * @param Queue $queue
     */
    public function __construct(State $state, Queue $queue)
    {
        $this->_queue = $queue;
        parent::__construct();
    }

    /**
     * Sets config for cli command
     */
    protected function configure()
    {
        $this->setName('springbot:queue:enqueue')
            ->setDescription('Enqueue Job')
            ->addArgument(self::CLASS_ARGUMENT, InputArgument::REQUIRED)
            ->addArgument(self::METHOD_ARGUMENT, InputArgument::REQUIRED)
            ->addArgument(self::QUEUE_ARGUMENT, null, '', 'default')
            ->addArgument(self::PRIORITY_ARGUMENT, null, '', 1)
            ->addArgument(self::PARAMS_ARGUMENT, InputArgument::IS_ARRAY);
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
            $successful = $this->_queue->scheduleJob(
                $input->getArgument(self::CLASS_ARGUMENT),
                $input->getArgument(self::METHOD_ARGUMENT),
                $input->getArgument(self::PARAMS_ARGUMENT),
                $input->getArgument(self::PRIORITY_ARGUMENT),
                $input->getArgument(self::QUEUE_ARGUMENT)
            );
            if ($successful) {
                $output->writeln("Job enqueued. " . $input->getArgument(self::CLASS_ARGUMENT));
            }
            else {
                $output->writeln("Job not enqueued.");
            }
        } catch (\Exception $e) {
            $output->writeln("Failed to enqueue job: " . $e->getMessage());
        }
    }
}
