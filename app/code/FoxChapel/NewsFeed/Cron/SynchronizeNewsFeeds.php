<?php

namespace FoxChapel\NewsFeed\Cron;

class SynchronizeNewsFeeds
{
    public function __construct(
        \FoxChapel\NewsFeed\Model\Data $newsFeed
    ) {
        $this->newsFeed = $newsFeed;
    }

    public function execute()
    {
        $channels = new Zend_Feed_Rss('https://foxchapelpublishing.com/news/feed/');

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