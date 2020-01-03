<?php

namespace FoxChapel\NewsFeed\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DataSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get data list.
     *
     * @return \FoxChapel\NewsFeed\Api\Data\DataInterface[]
     */
    public function getItems();

    /**
     * Set data list.
     *
     * @param \FoxChapel\NewsFeed\Api\Data\DataInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
