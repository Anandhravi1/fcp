<?php

namespace FoxChapel\AcumenIntegration\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use FoxChapel\AcumenIntegration\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;

class SendOrdersAsXmlInEmail
{
    /**
     * @var CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @var helperData
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var _storeId
     */
    protected $_storeId;

    /**
     * Cron constructor.
     * @param CollectionFactory $orderCollectionFactory
     * @param Data $helperData
     * @param DateTime $date
     */
    public function __construct(
        CollectionFactory $orderCollectionFactory,
        Data $helperData,
        DateTime $date,
        StoreManagerInterface $storeManager
    ) {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helperData = $helperData;
        $this->date = $date;
        $this->_storeManager = $storeManager;
    }

    public function sendOrders()
    {
        $stores = Mage::app()->getStores();

        foreach ($this->_storeManager->getStores() as $store) {
            $this->sendXmlAsEmail($store->getId());
        }
    }

    protected function sendXmlAsEmail($storeId)
    {
        $this->_storeId = $storeId;

        $orderCollection = $this->orderCollectionFactory->create()
            ->addAttributeToFilter('created_at', array('from' => $this->date->gmtDate('Y-m-d')))
            ->addFieldToFilter('store_id', $this->_storeId)
            ->addFieldToFilter('entity_id', array('gt' => $this->getLastIntegratedOrderId($this->_storeId)))
            ->load();
    }

    protected function getLastIntegratedOrderId($storeId)
    {
        if ($lastOrderId = $this->helperData->getGeneralConfig('last_integrated_order_id', $storeId)) {
            return $lastOrderId;
        }
        return 0;
    }
}
