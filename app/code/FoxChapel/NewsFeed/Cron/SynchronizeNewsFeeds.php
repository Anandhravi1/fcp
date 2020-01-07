<?php

namespace FoxChapel\NewsFeed\Cron;

use Zend\Feed\Reader as Feed;

class SynchronizeNewsFeeds
{
    private $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \FoxChapel\NewsFeed\Model\Data $newsFeed
    ) {
        $this->logger = $logger;
        $this->newsFeed = $newsFeed;
    }

    public function execute()
    {
        try {
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
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}