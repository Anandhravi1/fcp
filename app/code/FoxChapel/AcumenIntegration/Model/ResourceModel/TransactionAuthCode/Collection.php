<?php

namespace FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
	protected function _construct()
	{
		$this->_init(
            'FoxChapel\AcumenIntegration\Model\TransactionAuthCode', 'FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode'
        );
	}
}
