<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FoxChapel\Catalog\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\MessageQueue\ConsumerFactory;
use Magento\Framework\Lock\LockManagerInterface;

/**
 * Command for starting MessageQueue consumers.
 */
class StartConsumerCommand extends \Magento\MessageQueue\Console\StartConsumerCommand
{
    /**
     * @var ConsumerFactory
     */
    private $consumerFactory;

    /**
     * @var \Magento\Framework\App\State
     */
    private $appState;

    /**
     * @var LockManagerInterface
     */
    private $lockManager;
    /**
     * StartConsumerCommand constructor.
     * {@inheritdoc}
     *
     * @param \Magento\Framework\App\State $appState
     * @param ConsumerFactory $consumerFactory
     * @param string $name
     * @param LockManagerInterface $lockManager
     */
    public function __construct(
        \Magento\Framework\App\State $appState,
        ConsumerFactory $consumerFactory,
        $name = null,
        LockManagerInterface $lockManager = null
    ) {
        $this->appState = $appState;
        $this->consumerFactory = $consumerFactory;
        $this->lockManager = $lockManager ?: \Magento\Framework\App\ObjectManager::getInstance()
        ->get(LockManagerInterface::class);
        parent::__construct($appState, $consumerFactory, $name, $lockManager);
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $consumerName = $input->getArgument(self::ARGUMENT_CONSUMER);
        $numberOfMessages = $input->getOption(self::OPTION_NUMBER_OF_MESSAGES);
        $batchSize = (int)$input->getOption(self::OPTION_BATCH_SIZE);
        $areaCode = $input->getOption(self::OPTION_AREACODE);

        if ($input->getOption(self::PID_FILE_PATH)) {
            $input->setOption(self::OPTION_SINGLE_THREAD, true);
        }

        $singleThread = $input->getOption(self::OPTION_SINGLE_THREAD);

        if ($singleThread && $this->lockManager->isLocked(md5($consumerName))) { //phpcs:ignore
            $output->writeln('<error>Consumer with the same name is running</error>');
            return \Magento\Framework\Console\Cli::RETURN_FAILURE;
        }

        if ($singleThread) {
            $this->lockManager->lock(md5($consumerName)); //phpcs:ignore
        }

        try {
            $this->appState->setAreaCode($areaCode ?? 'global');
        } catch (\Exception $e) {
            // intentionally left empty
        }
        $consumer = $this->consumerFactory->get($consumerName, $batchSize);
        $consumer->process($numberOfMessages);

        if ($singleThread) {
            $this->lockManager->unlock(md5($consumerName)); //phpcs:ignore
        }

        return \Magento\Framework\Console\Cli::RETURN_SUCCESS;
    }    
}
