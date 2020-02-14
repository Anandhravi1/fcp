<?php

namespace FoxChapel\AcumenIntegration\Model\ResourceModel;


class TransactionAuthCode extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('transaction_auth_code', 'id');
	}
	
}