<?php

namespace FoxChapel\AcumenIntegration\Api\Data;

interface AuthCodeInterface
{

    const ID = 'id';
    const ORDER_ID = 'order_id';
    const CARD_TYPE = 'card_type';
    const AUTHORIZATION_CODE = 'authorization_code';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param $id
     * @return AuthCodeInterface
     */
    public function setId($id);

    /**
     * Get order_id
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param $order_id
     * @return mixed
     */
    public function setOrderId($order_id);

    /**
     * Get card_type
     *
     * @return string
     */
    public function getCardType();

    /**
     * Set card_type
     *
     * @param $card_type
     * @return mixed
     */
    public function setCardType($card_type);

    /**
     * Get authorization_code
     *
     * @return string
     */
    public function getAuthorizationCode();

    /**
     * Set authorization_code
     *
     * @param $authorization_code
     * @return mixed
     */
    public function setAuthorizationCode($authorization_code);

}
