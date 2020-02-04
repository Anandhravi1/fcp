<?php

namespace FoxChapel\AcumenIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * get configuration value
     *
     * @param [string] $field    
     * @param integer $storeId 
     *
     * @return void
     */
    public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}
}
