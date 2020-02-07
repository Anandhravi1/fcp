<?php

namespace FoxChapel\AcumenIntegration\Model;

use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use FoxChapel\AcumenIntegration\Helper\Data;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Payment\Model\Config;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;

class SendOrdersAsXmlInEmail
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

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
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * Cron constructor.
     * @param StoreManagerInterface $storeManager
     * @param CollectionFactory $orderCollectionFactory
     * @param OrderFactory $orderFactory
     * @param Order $order
     * @param Data $helperData
     * @param DateTime $date
     * @param Config $paymentConfig
     * @param TransportBuilder $transportBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $orderCollectionFactory,
        OrderFactory $orderFactory,
        Order $order,
        Data $helperData,
        DateTime $date,
        Config $paymentConfig,
        TransportBuilder $transportBuilder,
        LoggerInterface $logger
    ) {
        $this->_storeManager           = $storeManager;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderFactory           = $orderFactory;
        $this->_order                  = $order;
        $this->_helperData             = $helperData;
        $this->_date                   = $date;
        $this->_paymentConfig          = $paymentConfig;
        $this->_transportBuilder       = $transportBuilder;
        $this->logger                  = $logger;
    }

    /**
     * Cron method to send orders as XML in mail
     * 
     */
    public function sendOrders()
    {
        $this->logger->info('Getting Orders for Acumen Integration started');
        foreach ($this->_storeManager->getStores() as $store) {
            $storeId           = $store->getId();
            $emailSubject      = $this->_helperData->getGeneralConfig('email_subject', $storeId);
            $emailSender       = $this->_helperData->getGeneralConfig('email_sender', $storeId);
            $emailRecipients   = $this->getEmailRecipientList();
            $emailContentAsXml = $this->createOrdersXml($storeId);

            $transport = $this->_transportBuilder->setTemplateIdentifier(
                'orders_xml_email_template'
            )->setTemplateOptions(
                ['area' => 'adminhtml', 'store' => $storeId]
            )->setTemplateVars(
                ['orderXml' => $emailContentAsXml]
            )->setFrom(
                ['email' => $emailSender, 'name' => 'Magento FoxChapel Site']
            )->addTo(
                $emailRecipients
            )->getTransport();

            $transport->sendMessage();
        }
        $this->logger->info('Sending Orders XML for Acumen Integration completed');
    }

    /**
     * Create Orders XML
     * @param $storeId
     * 
     */
    protected function createOrdersXml($storeId)
    {
        $orderCollection = $this->_orderCollectionFactory->create()
            ->addAttributeToFilter('created_at', array('from' => $this->_date->gmtDate('Y-m-d')))
            ->addFieldToFilter('store_id', $storeId)
            ->addFieldToFilter('entity_id', array('gt' => $this->getLastIntegratedOrderId($storeId)))
            ->load();

        if ($orderCollection) {
            $dom = $this->createXmlFile();
            $root = $dom->createElement('Orders');

            $orderList = [];
            foreach ($orderCollection as $order) {
                $orderId = $order->getEntityId();
                array_push($orderList, $orderId);
                $this->_order = $this->_orderFactory->create()->load($orderId);
                $billingAddress = $this->_order->getBillingAddress();
                $shippingAddress = $this->_order->getShippingAddress();
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
                 
                $orderNode = $dom->createElement('Order');
                // TODO: Awaiting confirmation from FoxChapel
                // $this->appendXmlNode($dom, $orderNode, $this->getOrderReferer($order['entity_id']), 'RefererName');
                $this->appendXmlNode($dom, $orderNode, $this->_order['increment_id'], 'OrderNumber');

                $purchaseOrderNode = $dom->createElement('PurchaseOrder');
                $this->appendXmlNode($dom, $purchaseOrderNode, "FC2:" . $this->_order['increment_id'], 'PONumber');
                $orderNode->appendChild($purchaseOrderNode);
                
                $this->appendXmlNode($dom, $orderNode, "False", 'SpecialInstructions');

                if ($statusHistory = $this->_order->getStatusHistoryCollection()) {
                    $commentsNode = $dom->createElement('Comments');

                    foreach ($statusHistory as $status) {
                        if ($status->getComment()) {
                            $this->appendXmlNode($dom, $commentsNode, ' ', 'Comment');
                        }
                    }
                    $orderNode->appendChild($commentsNode);
                }
                
                $this->appendXmlNode($dom, $orderNode, date('m/d/Y', strtotime($order['created_at'])), 'OrderDate');
                $this->appendXmlNode($dom, $orderNode, "Invoice", 'DocumentType');
                $this->appendXmlNode($dom, $orderNode, '1.0', "DocumentVersion");
                $this->appendXmlNode($dom, $orderNode, $order['grand_total'], "OrderAmount");
                $this->appendXmlNode($dom, $orderNode, " ", "InvoiceType");
                $this->appendXmlNode($dom, $orderNode, "Website", "OrderMethod");

                $warehouseNode = $dom->createElement('Warehouse');
                $this->appendXmlNode($dom, $warehouseNode, 1002, "WarehouseID");
                $this->appendXmlNode($dom, $warehouseNode, "Fox Chapel Warehouse", "WarehouseName");
                $orderNode->appendChild($warehouseNode);

                $taxNode = $dom->createElement('Tax');
                $this->appendXmlNode($dom, $taxNode, $order['tax_amount'], 'Amount');
                $orderNode->appendChild($taxNode);

                $this->appendXmlNode($dom, $orderNode, "", "RepVendorID");
                $this->appendXmlNode($dom, $orderNode, $shipDesc, "ShipVIA");
                $this->appendXmlNode($dom, $orderNode, $order['shipping_amount'], "ShippingCharge");
                $orderNode->appendChild($this->appendOrderItems($orderId, $dom));
                $orderNode->appendChild($this->appendCardInfo($orderId, $dom));
                $orderNode->appendChild($this->appendAddressInformation($billingAddress, $dom, 'BillToParty'));
                $orderNode->appendChild($this->appendAddressInformation($shippingAddress, $dom, 'ShipToParty'));
                $this->appendXmlNode($dom, $orderNode, "Website", "Source");
                $this->appendXmlNode($dom, $orderNode, "Website", "ListCode");
                
                $root->appendChild($orderNode);
                $this->_helperData->saveConfig('last_integrated_order_id', $storeId);
            }
            $dom->appendChild($root);
            $dom->appendChild($root);
            $this->logger->info('Orders sent to Acumen Integration: ' . explode(',', $orderList) . ' for storeId: ' . $storeId);

            return $dom->saveXML();
        }

        return null;
    }

    /**
     * Get last_integrated_order_id from config_data
     * @param $storeId
     * 
     */
    protected function getLastIntegratedOrderId($storeId) {
        $lastOrderId = $this->_helperData->getGeneralConfig('last_integrated_order_id', $storeId);
        
        return $lastOrderId ? $lastOrderId : 0;
    }

     /**
     * Get Email Recipient list
     * @param $storeId
     * 
     * @return Array
     */
    protected function getEmailRecipientList($storeId) {
        $list = $this->_helperData->getGeneralConfig('email_recipients', $storeId);

        return $list !== null ? explode(',', $list) : [];
    }
    /**
     * Append Order line items to Order element
     * @param $orderId
     * @param $dom
     * 
     */
    protected function appendOrderItems($orderId, $dom) {
        $items      = $this->_order->getAllVisibleItems();
        $lineNumber = 1;
        $itemsNode  = $dom->createElement('LineItems');
        
        foreach ($items as $item) {
            
            $childItemNode    = $dom->createElement('ItemDetail');
            $listPrice        = $item->getPrice();
            $DiscountAmt      = $item->getDiscountAmount();
            $discountedPrice  = $listPrice - $DiscountAmt;
            $netPrice         = number_format($discountedPrice, 4);
            $qtyOrdered       = abs($item->getQtyOrdered());

            $this->appendXmlNode($dom, $childItemNode, $lineNumber, 'LineNumber');
            $this->appendXmlNode($dom, $childItemNode, $item->getSku(), 'ProductCode');
            $this->appendXmlNode($dom, $childItemNode, $item->getName(), 'Title');
            $this->appendXmlNode($dom, $childItemNode, $listPrice, 'ListPrice');
            $this->appendXmlNode($dom, $childItemNode, $netPrice, 'NetPrice');
            $this->appendXmlNode($dom, $childItemNode, $qtyOrdered, 'QtyOrdered');
            $itemsNode->appendChild($childItemNode);
            $lineNumber++;
        }

        return $itemsNode;
    }

    /**
     * Append card information
     * @param $orderId
     * @param $dom
     * 
     */
    protected function appendCardInfo($orderId, $dom) {
        $cardNode                 = $dom->createElement('CreditCardInfo');
        $paymentCode              = $this->_order->getPayment()->getMethodInstance()->getCode();
        $paymentPaypalPayerEmail  = $this->_order->getPayment()->getAdditionalInformation('paypal_payer_email');
        $paymentInfoAmountOrdered = $this->_order->getPayment()->getAmountOrdered();
        $paymentInfoLastTransId   = $this->_order->getPayment()->getLastTransId();
        $ccNumber                 = ($paymentCode == 'paypal_express') ? $paymentPaypalPayerEmail : '';
        $ccTransactionDate        = $this->date('m/d/Y', strtotime($orderId['created_at']));
        $ccExpirationDate         = ($paymentCode == 'paypal_express') ? "0130" : '';
        $ccAuthorizationService   = ($paymentCode == 'authnetcim') ? "Authorize.net" : $paymentCode;
        $ccPreAuthorization       = "TRUE";
        $sType                    = $this->_order->getPayment()->getCcType();
        $sName                    = $this->getCCTypeName($sType);
        $ccType                   = ($paymentCode == 'paypal_express') ? "PayPal" : $sName;
        // TODO: Need to develop custom module
        // $creditCardInformation = Mage::getModel('creatuity_cardinformation/card')
        //                           ->getCollection()
        //                           ->addFieldToFilter('order_id', $this->_order['increment_id'])
        //                           ->getFirstItem();
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

    /**
     * Append address information to Order element
     * @param $address
     * @param $dom
     * @param $nodeName
     * 
     */
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
     * Get CCType name
     * @param $ccType
     *
     */
    protected function getCCTypeName($ccType)
    {
        $types = $this->_paymentConfig->getCcTypes();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }

        return '';
    }

    /**
     * Create XML element
     * @param void
     *
     */
    protected function createXmlFile() {
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;

        return $dom;
    }

    /**
     * Append given node to XML
     * @param $dom
     * @param $node
     * @param $content
     * @param $childName
     * 
     */
    protected function appendXmlNode($dom, $node, $content, $childName) {
        $node->appendChild($dom->createElement($childName, $content));
    }
}
