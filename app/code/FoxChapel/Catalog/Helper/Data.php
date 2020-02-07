<?php
namespace FoxChapel\Catalog\Helper;
 
use Magento\Catalog\Helper\Data as MainHelper;
use Magento\Store\Model\ScopeInterface;
 
class Data extends MainHelper
{

    const HIDE_CATEGORY_FROM_BREADCRUMB = 'catalog/magento_catalogpermissions/hide_category_breadcrumb';

    public function getBreadcrumbPath()
    {
        $catid = 0;
       $catid =  $this->scopeConfig->getValue(
            self::HIDE_CATEGORY_FROM_BREADCRUMB,
            ScopeInterface::SCOPE_STORE
       );
        if (!$this->_categoryPath) {
            $path = [];
            $category = $this->getCategory();
            if ($category) {
                $pathInStore = $category->getPathInStore();
                $pathIds = array_reverse(explode(',', $pathInStore));

                $categories = $category->getParentCategories();

                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName() && ($categoryId != $catid)) {
                        $path['category' . $categoryId] = [
                            'label' => $categories[$categoryId]->getName(),
                            'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl() : ''
                        ];
                    }
                }
            }

            if ($this->getProduct()) {
                $path['product'] = ['label' => $this->getProduct()->getName()];
            }

            $this->_categoryPath = $path;
        }
        return $this->_categoryPath;
    }

    /**
     * To sort layered navigation filter by it's count using usort php function.
     *
     * @param Magento\Catalog\Model\Layer\Filter\Item $itemA
     * @param Magento\Catalog\Model\Layer\Filter\Item $itemB
     * @return boolean
     */
    public static function sortByCount($itemA, $itemB)
    {
        return (int)$itemA->getCount() < (int)$itemB->getCount();
    }
}
