<?php

namespace FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
	{
		$this->_init(
            'FoxChapel\AcumenIntegration\Model\TransactionAuthCode', 'FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode'
        );
	}
}
