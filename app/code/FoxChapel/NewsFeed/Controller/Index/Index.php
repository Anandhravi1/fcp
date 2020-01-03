<?php

namespace FoxChapel\NewsFeed\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Zend\Feed\Reader as Feed;

class Index extends Action
{
    public function __construct(
        Context $context,
        \FoxChapel\NewsFeed\Model\Data $newsFeed
    ) {
        $this->newsFeed = $newsFeed;
        parent::__construct($context);
    }

    public function execute()
    {
        $channels = Feed\Reader::import('https://foxchapelpublishing.com/news/feed/');
        foreach ($channels as $item) {
            $dateCreated = $item->getDateCreated();
            $dateFormatted = $dateCreated->format('Y-m-d H:i:s');
            $author = $item->getAuthor();

            $data = array('title'=> $item->getTitle(),
                        'guid' => $item->getId(),
                        'link' => $item->getLink(),
                        'short_description'=>$item->getDescription(),
                        'published_date' => $dateFormatted,
                        'creator' => $author['name']);
            $this->newsFeed->setData($data);
            $this->newsFeed->save();
        }
    }
}