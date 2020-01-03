<?php

namespace FoxChapel\NewsFeed\Model;

use Magento\Framework\Model\AbstractModel;
use FoxChapel\NewsFeed\Api\Data\DataInterface;

class Data extends AbstractModel implements DataInterface
{
    /**
     * Cache tag
     */
    const CACHE_TAG = 'news_feed';

    /**
     * Initialise resource model
     * @codingStandardsIgnoreStart
     */
    protected function _construct()
    {
        $this->_init('FoxChapel\NewsFeed\Model\ResourceModel\Data');
    }

    /**
     * Get cache identities
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(DataInterface::TITLE);
    }

    /**
     * Set title
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(DataInterface::TITLE, $title);
    }

    public function getGuid()
    {
        return $this->getData(DataInterface::GUID);
    }

    public function setGuid($guid)
    {
        return $this->setData(DataInterface::GUID, $guid);
    }

    public function getLink()
    {
        return $this->getData(DataInterface::LINK);
    }

    public function setLink($link)
    {
        return $this->setData(DataInterface::LINK, $link);
    }

    /**
     * Get data description
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->getData(DataInterface::SHORT_DESCRIPTION);
    }

    /**
     * Set data description
     *
     * @param $description
     * @return $this
     */
    public function setShortDescription($description)
    {
        return $this->setData(DataInterface::SHORT_DESCRIPTION, $description);
    }

    /**
     * Get is active
     *
     * @return bool|int
     */
    public function getPublishedDate()
    {
        return $this->getData(DataInterface::PUBLISHED_DATE);
    }

    /**
     * Set is active
     *
     * @param $isActive
     * @return $this
     */
    public function setPublishedDate($date)
    {
        return $this->setData(DataInterface::PUBLISHED_DATE, $date);
    }

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getData(DataInterface::CREATED_AT);
    }

    /**
     * Set created at
     *
     * @param $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(DataInterface::CREATED_AT, $createdAt);
    }

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->getData(DataInterface::UPDATED_AT);
    }

    /**
     * Set updated at
     *
     * @param $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(DataInterface::UPDATED_AT, $updatedAt);
    }
}
