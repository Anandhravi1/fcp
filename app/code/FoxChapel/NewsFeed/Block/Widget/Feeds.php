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
		array $data
	) {
		$this->newsFeedFactory = $newsFeed;
		parent::__construct($context, $data);
	}

	public function getFeeds()
	{
		$newsModel = $this->newsFeedFactory->create();
		$collection = $newsModel->getCollection();
		return $collection;
	}
}
