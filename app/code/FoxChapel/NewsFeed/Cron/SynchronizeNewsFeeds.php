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

            $this->deleteFeeds();

            foreach ($channels as $item) {
                $dateCreated = $item->getDateCreated();
                $dateFormatted = $dateCreated->format('Y-m-d H:i:s');
                $author = $item->getAuthor();
                $fullDescription = $item->getDescription();

                $last_chunk = strrpos($fullDescription, '>');
                $image_url = substr($fullDescription, 0, $last_chunk + 1);

                $shortDescription = substr($fullDescription, strrpos($fullDescription, '>' )+1);

                $data = array('title'=> $item->getTitle(),
                            'guid' => $item->getId(),
                            'link' => $item->getLink(),
                            'image_url' => $image_url,
                            'short_description'=> $shortDescription,
                            'published_date' => $dateFormatted,
                            'creator' => $author['name']);
                $this->newsFeed->setData($data);
                $this->newsFeed->save();
            }
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    /**
     * Removes the existing feeds
     */
    public function deleteFeeds()
    {
        $collection = $this->newsFeed->getCollection();
        $collection->walk('delete');
    }
}