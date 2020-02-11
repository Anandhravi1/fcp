<?php

namespace FoxChapel\AcumenIntegration\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\ReinitableConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ACUMEN_INTEGRATION = 'acumen_integration/';

    public function __construct(
        ScopeConfigInterface $scopeConfig,
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
     *
     * @return void
     */
    private function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            $field, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param string $path
     * 
     * @return void
     */
    public function getGeneralConfig($path)
    {
        return $this->getConfigValue(self::XML_PATH_ACUMEN_INTEGRATION .'general/'. $path);
    }

    /**
     * @param string $path
     * @param string $value
     * 
     * @return $this
     */
    public function saveConfig($path, $value)
    {
        $this->configWriter->save(self::XML_PATH_ACUMEN_INTEGRATION .'general/'. $path, $value);
        $this->reinitableConfig->reinit();
        
        return $this;
    }
}
