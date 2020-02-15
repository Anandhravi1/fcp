<?php

/**
 * Load and Render the index pages of sitemap
 *
 */

namespace FoxChapel\Sitemap\Controller\Index;

/**
 * Load and Render the index pages of sitemap
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Load and Render the layout
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();

        $this->_view->renderLayout();
    }
}
