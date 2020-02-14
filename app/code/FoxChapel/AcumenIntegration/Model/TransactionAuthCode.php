<?php

namespace FoxChapel\AcumenIntegration\Model;

class TransactionAuthCode extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
	{
		$this->_init('FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode');
	}
}
