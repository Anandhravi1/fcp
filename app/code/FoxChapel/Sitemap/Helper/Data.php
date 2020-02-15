<?php
/**
 * For Get the Store Config Values In SiteMap Config
 */

namespace FoxChapel\Sitemap\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * For Get the Store Config Values In SiteMap Config
 */
class Data extends AbstractHelper
{

    const XML_PATH_SITEMAP = 'sitemapConfig/';

    /**
     * Get the ScopeConfig Value by store
     *
     * @param string $field store field name
     * @param int $storeId store id
     *
     * @return ScopeInterface
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get a sitemap config values
     *
     * @param string $code  store config field id
     * @param int $storeId store id
     *
     * @return int
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(
            self::XML_PATH_SITEMAP .
            'html_sitemap_page/' . $code,
            $storeId
        );
    }
}
