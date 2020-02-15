<?php

/**
 * For SiteMap Block -- Collect the Cms Pages and Categories
 *
 */
namespace FoxChapel\Sitemap\Block;

/**
 * Inject the customer Session
 */
use \Magento\Customer\Model\Session;

/**
 * For SiteMap Block
 */
class Sitemap extends \Magento\Framework\View\Element\Template
{
    /**
     * Page Collection Factory Variable
     *
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectFactory
     */
    public $pageCollectFactory;
    /**
     * Category Helper
     *
     * @var \Magento\Catalog\Helper\Category $categoryHelper
     */
    public $categoryHelper;
    /**
     * Category Flat Configuration Variable
     *
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatConfig
     */
    public $categoryFlatConfig;
    /**
     * Sitemap Helper Class Variable
     *
     * @var \FoxChapel\Sitemap\Helper\Data $siteMapHelper
     */
    public $siteMapHelper;
    /**
     * Customer Session Variable
     *
     * @var \Magento\Customer\Model\Session $customerSession
     */
    public $customerSession;
    /**
     * Get the Store details
     *
     * @var \Magento\Store\Model\StoreManagerInterface $storeInterface
     */
    public $storeInterface;

    /**
     * Ignore the Cms Pages for these pages id are not showing
     * the frontend of cms pages in sitemap
     */
    public $ignorePageId = [ 'no-route', 'enable-cookies',
                              'privacy-policy-cookie-restriction-mode',
                              'service-unavailable', 'amp_homepage',
                              'private-sales', 'reward-points'
                            ];

    /**
     * Inject the Page Collection Factory, Category Helper and customer session
     *
     * @param \Magento\Framework\View\Element\Template\Context $context  Constructor modification
     * @param \Magento\Catalog\Helper\Category $categoryHelper Helper Class
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState Flat State
     * @param \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     * $pageCollectFactory Collection of Pages
     * @param \FoxChapel\Sitemap\Helper\Data $siteMapHelper Helper Class
     * @param Session $customerSession Customer Session model
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectFactory,
        \FoxChapel\Sitemap\Helper\Data $siteMapHelper,
        \Magento\Catalog\Model\CategoryFactory $categoryModel,
        \Magento\Framework\App\Http\Context $httpContext
    ) {
        $this->pageCollectionFactory = $pageCollectFactory;
        $this->categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->siteMapHelper = $siteMapHelper;
        $this->httpContext = $httpContext;
        $this->categoryModel = $categoryModel;
        $this->storeInterface = $context->getStoreManager();
        parent::__construct($context);
    }

    /**
     * Get the BaseUrl
     *
     * @return string Get the store url
     */
    public function getBaseUrl()
    {
        $url = $this->storeInterface->getStore()->getBaseUrl();
        return $url;
    }

    /**
     * Get the Cms Page Collection
     *
     * @return string page collection
     */
    public function getCreareCMSPages()
    {
        $collectionCmsPages = $this->pageCollectionFactory->create();
        $storeId = $this->storeInterface->getStore()->getId();
        $statusEnabled = \Magento\Cms\Model\Page::STATUS_ENABLED;

        $collectionCmsPages->addFieldToFilter('is_active', $statusEnabled)
            ->addFieldToFilter('identifier', ['nin' => $this->ignorePageId ])
            ->addStoreFilter($storeId);

        return $collectionCmsPages;
    }

    /**
     * Get the Category Helper class
     *
     * @return \Magento\Catalog\Helper\Category Get the Category Helper class
     */
    public function getCategoryHelper()
    {
        return $this->categoryHelper;
    }

    /**
     * Retrieve current store categories
     *
     * @param bool $sorted Sorting field flag
     * @param bool $asCollection Collection flag
     * @param bool $toLoad To load flag
     *
     * @return void
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }
    /**
     * Load the Categories by categories id
     *
     * @param int $categoryId
     *
     * @return \Magento\Catalog\Model\CategoryFactory
     */
    public function getCategories($categoryId)
    {
        return $this->categoryModel->create()->load($categoryId);
    }
    /**
     * Retrieve the Sitemap Store Config Values
     *
     * @return \FoxChapel\Sitemap\Helper\Data Helper Class
     */
    public function getStoreConfigValues()
    {
        return $this->siteMapHelper;
    }

    /**
     * Retrieve the Customer Session
     *
     * @return boolean Logged session
     */
    public function isUserLoggedIn()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }
}
