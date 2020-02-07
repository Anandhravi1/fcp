<?php

namespace FoxChapel\AcumenIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ACUMEN_INTEGRATION = 'acumen_integration/';

    public function __construct(
        ScopeInterface $scopeConfig,
        ReinitableConfigInterface $reinitableConfig, 
        WriterInterface $configWriter
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->reinitableConfig = $reinitableConfig;
        $this->configWriter = $configWriter;
    }
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

    /**
     * @param string $path
     * @param integer $storeId
     * 
     * @return void
     */
    public function getGeneralConfig($path, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ACUMEN_INTEGRATION .'general/'. $path, $storeId);
    }

    /**
     * @param string $path
     * @param string $value
     * 
     * @return $this
     */
    private function saveConfig($path, $value)
    {
        $this->configWriter->save(self::XML_PATH_ACUMEN_INTEGRATION .'general/'. $path, $value);
        $this->reinitableConfig->reinit();
        
        return $this;
    }
}
