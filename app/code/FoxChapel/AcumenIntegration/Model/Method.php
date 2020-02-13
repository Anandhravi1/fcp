<?php

namespace FoxChapel\AcumenIntegration\Model;

use Psr\Log\LoggerInterface;

/**
 * Authorize.Net CIM payment method
 */
class FoxChapel_AcumenIntegration_Model_Method extends \ParadoxLabs\Authnetcim\Model\Method
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param \Magento\Sales\Model\Order\Payment\Transaction\Repository $transactionRepository
     * @param \ParadoxLabs\TokenBase\Helper\Data $helper
     * @param \ParadoxLabs\TokenBase\Model\AbstractGateway $gateway
     * @param \ParadoxLabs\TokenBase\Api\Data\CardInterfaceFactory $cardFactory
     * @param \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository
     * @param \ParadoxLabs\TokenBase\Helper\Address $addressHelper *Proxy
     * @param \Magento\Payment\Gateway\ConfigInterface $config
     * @param \Magento\Framework\Registry $registry
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Sales\Model\Order\Payment\Transaction\Repository $transactionRepository,
        \ParadoxLabs\TokenBase\Helper\Data $helper,
        \ParadoxLabs\TokenBase\Model\AbstractGateway $gateway,
        \ParadoxLabs\TokenBase\Api\Data\CardInterfaceFactory $cardFactory,
        \ParadoxLabs\TokenBase\Api\CardRepositoryInterface $cardRepository,
        \ParadoxLabs\TokenBase\Helper\Address $addressHelper,
        \Magento\Payment\Gateway\ConfigInterface $config,
        \Magento\Framework\Registry $registry,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;

        parent::__construct(
            $transactionRepository,
            $helper,
            $gateway,
            $cardFactory,
            $addressHelper,
            $config,
            $registry,
            $methodCode = '',
            $data = []
        );

        parent::__construct(
            $data
        );
    }

    /**
     * Authorize a transaction
     *
     * @param \Magento\Payment\Model\InfoInterface $payment
     * @param float $amount
     * @return $this
     */
    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        parent::authorize($payment, $amount);
        

        $response = $this->gateway()->getLastResponse();
        $this->logger->info(var_dump($response));


    }

}
