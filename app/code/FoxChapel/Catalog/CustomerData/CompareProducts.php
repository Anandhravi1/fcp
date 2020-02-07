<?php

namespace FoxChapel\Catalog\CustomerData;

use Magento\Catalog\CustomerData\CompareProducts as Compare;

class CompareProducts extends Compare
{
    /**
     * @param \Magento\Catalog\Helper\Product\Compare $helper
     * @param \Magento\Catalog\Model\Product\Url $productUrl
     * @param \Magento\Catalog\Helper\Output $outputHelper
     */
    public function __construct(
        \Magento\Catalog\Helper\Product\Compare $helper,
        \Magento\Catalog\Model\Product\Url $productUrl,
        \Magento\Catalog\Helper\Output $outputHelper
    ) {
        parent::__construct(
            $helper,
            $productUrl,
            $outputHelper
        );
    }

    /**
     * To return compare products count caption
     *
     * @return array
     */
    public function getSectionData()
    {
        $count = $this->helper->getItemCount();
        return [
            'count' => $count,
            'countCaption' => __('%1', $count),
            'listUrl' => $this->helper->getListUrl(),
            'items' => $count ? $this->getItems() : [],
        ];
    }
}
