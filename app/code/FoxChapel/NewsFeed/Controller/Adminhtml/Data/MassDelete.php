<?php

namespace FoxChapel\NewsFeed\Controller\Adminhtml\Data;

use FoxChapel\NewsFeed\Model\Data;

class MassDelete extends MassAction
{
    /**
     * @param Data $data
     * @return $this
     */
    protected function massAction(Data $data)
    {
        $this->dataRepository->delete($data);
        return $this;
    }
}
