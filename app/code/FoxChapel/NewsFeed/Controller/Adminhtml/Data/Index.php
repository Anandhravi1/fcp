<?php

namespace FoxChapel\NewsFeed\Controller\Adminhtml\Data;

use FoxChapel\NewsFeed\Controller\Adminhtml\Data;

class Index extends Data
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('News Feeds'));
        return $resultPage;
    }
}
