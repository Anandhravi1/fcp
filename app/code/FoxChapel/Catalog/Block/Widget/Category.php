<?php

namespace FoxChapel\Catalog\Block\Widget;

use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Block\Navigation;

class Category extends Navigation implements BlockInterface
{
    protected $_template = "widget/sub_categories.phtml";

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $assetRepos;

    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $helperImageFactory;    


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Catalog\Helper\Category $catalogCategory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $flatState
     * @param \Magento\Framework\View\Asset\Repository $assetRepos
     * @param \Magento\Catalog\Helper\ImageFactory $helperImageFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Catalog\Helper\Category $catalogCategory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $flatState,
        \Magento\Framework\View\Asset\Repository $assetRepos,
        \Magento\Catalog\Helper\ImageFactory $helperImageFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $categoryFactory,
            $productCollectionFactory,
            $layerResolver,
            $httpContext,
            $catalogCategory,
            $registry,
            $flatState,
            $data
        );
        $this->assetRepos = $assetRepos;
        $this->helperImageFactory = $helperImageFactory;        
    }

    /**
     * To get widget title
     *
     * @return string
     */
    public function getWidgetTitle()
    {
        return $this->getTitle();
    }

    /**
     * To get widget template view mode GRID | LIST
     *
     * @return string
     */
    public function getTemplateMode()
    {
        return $this->getTemplateType();
    }

    /**
     * To get category image url
     *
     * @param \Magento\Catalog\Model\CategoryFactory $category
     * @return string
     */
    public function getCategoryImage($category)
    {
        $category = $this->_categoryInstance->load($category->getEntityId());

        if ($category->getImageUrl()) {
            return $category->getImageUrl();
        } else {
            $imagePlaceholder = $this->helperImageFactory->create();
            return $this->assetRepos->getUrl($imagePlaceholder->getPlaceholder('small_image'));
        }
    }
}
