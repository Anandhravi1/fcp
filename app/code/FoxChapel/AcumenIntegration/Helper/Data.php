<?php

namespace FoxChapel\AcumenIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ACUMEN_INTEGRATION = 'acumen_integration/';

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

	public function getGeneralConfig($code, $storeId = null)
	{
		return $this->getConfigValue(self::XML_PATH_ACUMEN_INTEGRATION .'general/'. $code, $storeId);
	}
}
