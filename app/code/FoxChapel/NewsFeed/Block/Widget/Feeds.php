<?php

namespace FoxChapel\NewsFeed\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface; 
use Magento\Framework\View\Element\Template\Context;
 
class Feeds extends Template implements BlockInterface {

	protected $_template = "widget/feeds.phtml";

	/**
	 *  @var \FoxChapel\NewsFeed\Model\DataFactory
	 */
	protected $newsFeedFactory;

	public function __construct(
		Context $context,
		\FoxChapel\NewsFeed\Model\DataFactory $newsFeed,
		\Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
		array $data
	) {
		$this->timezoneInterface = $timezoneInterface;
		$this->newsFeedFactory = $newsFeed;
		parent::__construct($context, $data);
	}

	public function getFeeds()
	{
		$newsModel = $this->newsFeedFactory->create();
		$limit = $this->_scopeConfig->getValue(
			'foxchapel_config/general/feed_count',
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
		$collection = $newsModel->getCollection()->setPageSize($limit);
		return $collection;
	}

	public function isEnabled()
	{
		return $this->_scopeConfig->getValue(
			'foxchapel_config/general/enable',
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}

	public function getFormattedDate($pubDate)
	{
		$formattedDate = $this->timezoneInterface->date($pubDate)->format('d M');
		return $formattedDate;
	}
}
