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
        CollectionFactory $categoryCollection,
        StoreManagerInterface $storeManager,
        Category $categoryModel,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryModel = $categoryModel;
        $this->logger = $logger;
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

        if ($childLevel == 2) {
            $categoryImages = $this->getCategoryImage($menuId);
            foreach ($categoryImages as $image) {
                $html .= '<div class="category_image">'
                    . '<img src="' . $image . '"/></div>';
            }
        }
        $transport->setHtml($html);
    }

    /**
     * To get category image URL
     * 
     * @param int $categoryId
     * @return string
     */
    protected function getCategoryImage($categoryId)
    {   
        $categoryIdElements = explode('-', $categoryId);
       
        $category = $this->categoryRepository->get(end($categoryIdElements));
        $menuFirstImage = $category->getMenuFirstImage();
        $menuSecondImage = $category->getMenuSecondImage();
        $categoryImage = [];
        $mediaUrl = $this->getMediaUrl();
        if (!empty($menuFirstImage)) {
            $categoryImage[] = $mediaUrl . SELF::CATEGORY_IMG_PATH . $menuFirstImage;
        }
        if (!empty($menuSecondImage)) {
            $categoryImage[] = $mediaUrl . SELF::CATEGORY_IMG_PATH . $menuSecondImage;
        }
        return $categoryImage;
    }

    /**
     * To get medial URL
     * 
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }
}
