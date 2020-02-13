<?php

namespace FoxChapel\Catalog\Model;

use Magento\Catalog\Model\Category;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator as Magento_CategoryUrlPathGenerator;

class CategoryUrlPathGenerator extends Magento_CategoryUrlPathGenerator 
{
    public function getUrlPath($category, $parentCategory = null) 
    {      
        if (in_array($category->getParentId(), [Category::ROOT_CATEGORY_ID, Category::TREE_ROOT_ID])) {
            return '';
        }
        $path = $category->getUrlPath();
        if ($path !== null && !$category->dataHasChangedFor('url_key') && !$category->dataHasChangedFor('parent_id')) {
            if($this->scopeConfig->getValue('catalog/category_customurlrewrite/hide_category', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
                $categoryId = $this->scopeConfig->getValue('catalog/category_customurlrewrite/category_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $parentCategory = $this->categoryRepository->get($categoryId, $category->getStoreId());
                $path = str_replace($parentCategory->getUrlPath()."/","",$path);
            }
            return $path;
        }
        $path = $category->getUrlKey();
        if ($path === false) {
            return $category->getUrlPath();
        }
        if ($this->isNeedToGenerateUrlPathForParent($category)) {
            $parentCategory = $parentCategory === null ?
                $this->categoryRepository->get($category->getParentId(), $category->getStoreId()) : $parentCategory;
            $parentPath = $this->getUrlPath($parentCategory);
            $path = $parentPath === '' ? $path : $parentPath . '/' . $path;
        }
        return $path;
    }
}