<?php

namespace FoxChapel\Header\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class Topmenu implements ObserverInterface
{

    const CATEGORY_IMG_PATH = 'catalog/category/';

    protected $categoryRepository;
    protected $categoryCollection;
    protected $storeManager;

    
    /**
     * 
     * @param CollectionFactory           $categoryCollection
     * @param StoreManagerInterface       $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Cms\Model\BlockFactory $cmsBlock,
        \Magento\Cms\Model\Template\FilterProvider $filter,
        CollectionFactory $categoryCollection,
        StoreManagerInterface $storeManager,
        Category $categoryModel,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryModel = $categoryModel;
        $this->logger = $logger;
        $this->cmsBlock = $cmsBlock;
        $this->blockFilter = $filter;
        $this->_categoryCollection = $categoryCollection;
        $this->_storeManager = $storeManager;
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * Execute Method
     * 
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $transport = $observer->getTransport();
        $html = $transport->getHtml();
        $menuTree = $transport->getMenuTree();

        $parentLevel = $menuTree->getLevel();        

        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $menuId = $menuTree->getId();

        $categoryIdElements = explode('-', $menuId);

        $block = $this->cmsBlock->create()->load('menu_image_'.end($categoryIdElements));
        
        if ($childLevel == 2) {
            if ($block->isActive()) {
                $filterContent = $this->blockFilter->getPageFilter()->filter($block->getContent());
                $html .= '<div class="category_image">' .  $filterContent . '</div>';
            }
        }
        $transport->setHtml($html);
    }

}
