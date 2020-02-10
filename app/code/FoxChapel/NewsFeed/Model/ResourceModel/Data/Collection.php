<?php

namespace FoxChapel\NewsFeed\Model\ResourceModel\Data;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     * @codingStandardsIgnoreStart
     */
    protected $_idFieldName = 'id';

    /**
     * Collection initialisation
     */
    protected function _construct()
    {
        // @codingStandardsIgnoreEnd
        $this->_init('FoxChapel\NewsFeed\Model\Data', 'FoxChapel\NewsFeed\Model\ResourceModel\Data');
    }
}
