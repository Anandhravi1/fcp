<?php

namespace FoxChapel\AcumenIntegration\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class TransactionAuthCode extends AbstractDb
{
	
	public function __construct(Context $context)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('transaction_auth_code', 'id');
	}
	
}