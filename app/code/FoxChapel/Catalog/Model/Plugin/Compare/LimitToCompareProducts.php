<?php

namespace FoxChapel\Catalog\Model\Plugin\Compare;

use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Store\Model\ScopeInterface;

class LimitToCompareProducts
{

 const COMPARE_PRODUCTS_LIMIT = 'catalog/recently_products/compare_product_limit';
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var RedirectFactory
     */
    protected $resultRedirectFactory;

    /** @var Compare */
    protected $helper;

    /**
     * RestrictCustomerEmail constructor.
     * @param Compare $helper
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RedirectFactory $redirectFactory,
        Compare $helper,
        ManagerInterface $messageManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->helper = $helper;
        $this->resultRedirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->_scopeConfig = $scopeConfig;
    }

     public function aroundExecute(
    \Magento\Catalog\Controller\Product\Compare\Add $subject,
     \Closure $proceed
    ){
    $count_limit = 0;
     $count_limit =  $this->_scopeConfig->getValue(
            self::COMPARE_PRODUCTS_LIMIT,
            ScopeInterface::SCOPE_STORE
       );
       
      $count = $this->helper->getItemCount();
      $resultRedirect = $this->resultRedirectFactory->create();
      if($count >= $count_limit) {
     
        $this->messageManager->addErrorMessage(
            'The max number of compared products is 3'
        );
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
       return $resultRedirect->setRefererOrBaseUrl();
      }

      return $proceed();
        
   }
}
