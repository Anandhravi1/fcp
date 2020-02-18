<?php

namespace FoxChapel\AcumenIntegration\Model;

use Magento\Framework\Model\AbstractModel;
use FoxChapel\AcumenIntegration\Api\Data\AuthCodeInterface;

class TransactionAuthCode extends AbstractModel implements AuthCodeInterface
{
    protected function _construct()
	{
		$this->_init('FoxChapel\AcumenIntegration\Model\ResourceModel\TransactionAuthCode');
	}

	/**
     * Get order_id
     *
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData(AuthCodeInterface::ORDER_ID);
    }

    /**
     * Set order_id
     *
     * @param $order_id
     * @return $this
     */
    public function setOrderId($order_id)
    {
        return $this->setData(AuthCodeInterface::ORDER_ID, $order_id);
	}
	
	/**
     * Get card_type
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->getData(AuthCodeInterface::CARD_TYPE);
    }

    /**
     * Set card_type
     *
     * @param $card_type
     * @return $this
     */
    public function setCardType($card_type)
    {
        return $this->setData(AuthCodeInterface::CARD_TYPE, $card_type);
	}
	
	/**
     * Get card_type
     *
     * @return string
     */
    public function getAuthorizationCode()
    {
        return $this->getData(AuthCodeInterface::AUTHORIZATION_CODE);
    }

    /**
     * Set authorization_code
     *
     * @param $authorization_code
     * @return $this
     */
    public function setAuthorizationCode($authorization_code)
    {
        return $this->setData(AuthCodeInterface::AUTHORIZATION_CODE, $authorization_code);
    }
}
