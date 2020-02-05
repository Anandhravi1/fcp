<?php

namespace FoxChapel\AcumenIntegration\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use FoxChapel\AcumenIntegration\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Payment\Model\Config;

class SendOrdersAsXmlInEmail
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var _storeId
     */
    protected $_storeId;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \FoxChapel\AcumenIntegration\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentConfig;

    /**
     * Cron constructor.
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $orderCollectionFactory
     * @param OrderFactory $orderFactory
     * @param Order $order
     * @param Data $helperData
     * @param DateTime $date
     * @param Config $paymentConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $orderCollectionFactory,
        OrderFactory $orderFactory,
        Order $order,
        Data $helperData,
        DateTime $date,
        Config $paymentConfig
    ) {
        $this->_storeManager           = $storeManager;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderFactory           = $orderFactory;
        $this->_order                  = $order;
        $this->helperData              = $helperData;
        $this->_date                   = $date;
        $this->paymentConfig           = $paymentConfig;
    }

    public function sendOrders()
    {
        foreach ($this->_storeManager->getStores() as $store) {
            $this->sendXmlAsEmail($store->getId());
        }
    }

    protected function sendXmlAsEmail($storeId)
    {
        $this->_storeId = $storeId;

        $orderCollection = $this->_orderCollectionFactory->create()
            ->addAttributeToFilter('created_at', array('from' => $this->_date->gmtDate('Y-m-d')))
            ->addFieldToFilter('store_id', $this->_storeId)
            ->addFieldToFilter('entity_id', array('gt' => $this->getLastIntegratedOrderId($this->_storeId)))
            ->load();

        if ($orderCollection) {
            // <Orders> 
            $dom = $this->createXmlFile();
            $root = $dom->createElement('Orders');

            foreach ($orderCollection as $order) {
                $orderId = $order->getEntityId();
                $this->_order = $this->_orderFactory->create()->load($orderId);
                $billingAddress = $this->_order->getBillingAddress();
                $shippingAddress = $this->_order->getShippingAddress();
                 
                // <Order><OrderNumber>
                $orderNode = $dom->createElement('Order');
                $this->appendXmlNode($dom, $orderNode, $this->_order['increment_id'], 'OrderNumber');

                // <PurchaseOrder><PONumber>
                $purchaseOrderNode = $dom->createElement('PurchaseOrder');
                $this->appendXmlNode($dom, $purchaseOrderNode, "FC2:" . $this->_order['increment_id'], 'PONumber');
                $orderNode->appendChild($purchaseOrderNode);
                
                // <SpecialInstructions>
                $this->appendXmlNode($dom, $orderNode, "False", 'SpecialInstructions');

                // <Comments><Comment>
                if ($statusHistory = $this->_order->getStatusHistoryCollection()) {
                    $commentsNode = $dom->createElement('Comments');

                    foreach ($statusHistory as $status) {
                        if ($status->getComment()) {
                            $this->appendXmlNode($dom, $commentsNode, ' ', 'Comment');
                        }
                    }
                    $orderNode->appendChild($commentsNode);
                }
                
                // <OrderDate><DocumentType><DocumentVersion><OrderAmount><InvoiceType><OrderMethod>
                $this->appendXmlNode($dom, $orderNode, date('m/d/Y', strtotime($order['created_at'])), 'OrderDate');
                $this->appendXmlNode($dom, $orderNode, "Invoice", 'DocumentType');
                $this->appendXmlNode($dom, $orderNode, '1.0', "DocumentVersion");
                $this->appendXmlNode($dom, $orderNode, $order['grand_total'], "OrderAmount");
                $this->appendXmlNode($dom, $orderNode, " ", "InvoiceType");
                $this->appendXmlNode($dom, $orderNode, "Website", "OrderMethod");

                // <Warehouse><WarehouseID><WarehouseName>
                $warehouseNode = $dom->createElement('Warehouse');
                $this->appendXmlNode($dom, $warehouseNode, 1002, "WarehouseID");
                $this->appendXmlNode($dom, $warehouseNode, "Fox Chapel Warehouse", "WarehouseName");
                $orderNode->appendChild($warehouseNode);

                // <Tax><Amount><RepVendorID>
                $taxNode = $dom->createElement('Tax');
                $this->appendXmlNode($dom, $taxNode, $order['tax_amount'], 'Amount');
                $orderNode->appendChild($taxNode);
                $this->appendXmlNode($dom, $orderNode, "", "RepVendorID");

                // <ShipVIA><ShippingCharge>
                $shipDesc = strtoupper($order['shipping_description']);
                switch (true) {
                    case strpos($shipDesc, 'DHL') !== false:
                        $shipDesc = 'US Standard Shipping';
                        break;
                    case strpos($shipDesc, 'INTERNATIONAL SERVICE') !== false:
                        $shipDesc = 'USPS Priority Mail INTL ';
                        break;
                    case strpos($shipDesc, 'PRIORITY MAIL') !== false:
                        $shipDesc = 'Priority Mail USPS';
                        break;
                    default:
                        $shipDesc = 'US Standard Shipping';
                }
                $this->appendXmlNode($dom, $orderNode, $shipDesc, "ShipVIA");
                $this->appendXmlNode($dom, $orderNode, $order['shipping_amount'], "ShippingCharge");

                // <LineItems><CreditCardInfo>
                $orderNode->appendChild($this->appendOrderItems($orderId, $dom));
                $orderNode->appendChild($this->appendCardInfo($orderId, $dom));
                $orderNode->appendChild($this->appendAddressInformation($billingAddress, $dom, 'BillToParty'));
                $orderNode->appendChild($this->appendAddressInformation($shippingAddress, $dom, 'ShipToParty'));
                $this->appendXmlNode($dom, $orderNode, "Website", "Source");
                $this->appendXmlNode($dom, $orderNode, "Website", "ListCode");
                
                $root->appendChild($orderNode);
                $this->helperData->saveConfig('last_integrated_order_id', $this->_storeId);
            }
            $dom->appendChild($root);
            $dom->appendChild($root);

            return $dom->saveXML();
        }
    }

    protected function getLastIntegratedOrderId($storeId) {
        $lastOrderId = $this->helperData->getGeneralConfig('last_integrated_order_id', $storeId);
        
        return $lastOrderId ? $lastOrderId : 0;
    }

    protected function appendOrderItems($orderId, $dom) {
        $items = $this->order->getAllVisibleItems();
        $i = 1;
        $itemsNode = $dom->createElement('LineItems');
        
        foreach ($items as $item) {
			
            $Price = $item->getPrice();
            $DiscountAmt = $item->getDiscountAmount();
            $NetPrice = $Price - $DiscountAmt;
            $NetPrice2 = number_format($NetPrice, 4);
            $childItemNode = $dom->createElement('ItemDetail');
            $this->appendXmlNode($dom, $childItemNode, $i, 'LineNumber');
            $this->appendXmlNode($dom, $childItemNode, $item->getSku(), 'ProductCode');
            $this->appendXmlNode($dom, $childItemNode, $item->getName(), 'Title');
            $this->appendXmlNode($dom, $childItemNode, $item->getPrice(), 'ListPrice');
			$this->appendXmlNode($dom, $childItemNode, $NetPrice2, 'NetPrice');
			
            $this->appendXmlNode($dom, $childItemNode, abs($item->getQtyOrdered()), 'QtyOrdered');
            $itemsNode->appendChild($childItemNode);
            $i++;
        }
        return $itemsNode;
    }

    protected function appendCardInfo($orderId, $dom) {
        $cardNode = $dom->createElement('CreditCardInfo');
        $paymentCode = $this->_order->getPayment()->getMethodInstance()->getCode();
        $paymentInfo = $this->_order->getPayment()->debug();
        $paymentInfoAmountOrdered = $this->_order->getPayment()->getAmountOrdered();
        $paymentInfoLastTransId = $this->_order->getPayment()->getLastTransId();
        $ccNumber = ($paymentCode == 'paypal_express') ? $paymentInfo['additional_information']['paypal_payer_email'] : '';
        $ccTransactionDate = $this->date('m/d/Y', strtotime($orderId['created_at']));
        $ccExpirationDate = ($paymentCode == 'paypal_express') ? "0130" : '';
        $ccAuthorizationService = ($paymentCode == 'authnetcim') ? "Authorize.net" : $paymentCode;
        $ccPreAuthorization = "TRUE";
        $sType = $this->_order->getPayment()->getCcType();
        $sName = $this->getCCTypeName($sType);
        $ccType = ($paymentCode == 'paypal_express') ? "PayPal" : $sName;
        // TODO: Need to develop custom module
        // $creditCardInformation = Mage::getModel('creatuity_cardinformation/card')
        //         ->getCollection()
        //         ->addFieldToFilter('order_id', $this->_order['increment_id'])
        //         ->getFirstItem();
				
        $this->appendXmlNode($dom, $cardNode, $ccNumber, 'CCNumber');
        $this->appendXmlNode($dom, $cardNode, $ccTransactionDate, 'CCTransactionDate');
        $this->appendXmlNode($dom, $cardNode, $ccExpirationDate, 'CCExpirationDate');
        // $this->appendXmlNode($dom, $cardNode, $creditCardInformation['authorization_code'], 'CCAuthorizationNumber');
        $this->appendXmlNode($dom, $cardNode, $ccAuthorizationService, 'CCAuthorizationService');
        $this->appendXmlNode($dom, $cardNode, $ccPreAuthorization, 'CCPreAuthorization');
        $this->appendXmlNode($dom, $cardNode, $paymentInfoLastTransId, 'CCTransRefNumber');
        $this->appendXmlNode($dom, $cardNode, $ccType, 'CCType');
        $this->appendXmlNode($dom, $cardNode, $paymentInfoAmountOrdered, 'CCAmount');

        return $cardNode;
    }

    protected function appendAddressInformation($address, $dom, $nodeName) {
        $addressNode = $dom->createElement($nodeName);
        $this->appendXmlNode($dom, $addressNode, $address->getStreet(), 'PostalAddress1');
        $this->appendXmlNode($dom, $addressNode, "", 'PostalAddress2');
        $this->appendXmlNode($dom, $addressNode, $address->getCity(), 'PostalTownCity');
        $this->appendXmlNode($dom, $addressNode, $address->getCountryId(), 'Country');
        $this->appendXmlNode($dom, $addressNode, $address->getFax(), 'Fax');
        $this->appendXmlNode($dom, $addressNode, $address->getFirstname(), 'PartyFirstName');
        $this->appendXmlNode($dom, $addressNode, $address->getLastname(), 'PartyName');
        $this->appendXmlNode($dom, $addressNode, $address->getTelephone(), 'Phone');
        $this->appendXmlNode($dom, $addressNode, $address->getPostcode(), 'PostalCode');
        $this->appendXmlNode($dom, $addressNode, $address->getRegionCode(), 'PostalState');
        $this->appendXmlNode($dom, $addressNode, $address->getEmail(), 'Email');
        $this->appendXmlNode($dom, $addressNode, "Consumer", 'CustomerCategory');
        $this->appendXmlNode($dom, $addressNode, "Consumer", 'CustomerType');
        $this->appendXmlNode($dom, $addressNode, "Grid Discounts", 'CustomerDiscountType');
        $this->appendXmlNode($dom, $addressNode, "False", 'CustomerUsePrice2');
        $this->appendXmlNode($dom, $addressNode, "Consumer", 'CustomerClass');

        return $addressNode;
    }

    /**
     * @param $ccType
     *
     * @return mixed
     */
    protected function getCCTypeName($ccType)
    {
        $types = $this->paymentConfig->getCcTypes();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }

        return '';
    }

    protected function createXmlFile() {
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;

        return $dom;
    }

    protected function appendXmlNode($dom, $node, $content, $childName) {
        $node->appendChild($dom->createElement($childName, $content));
    }

    

    
}
