<?php

namespace FoxChapel\AcumenIntegration\Plugin;

use FoxChapel\AcumenIntegration\Model\TransactionAuthCode;

class AuthorizeResponse
{
    /**
     * @var transactionAuthCode
     */
    protected $transactionAuthCode;

    public function __construct(
        TransactionAuthCode $transactionAuthCode
    ) {
        $this->transactionAuthCode = $transactionAuthCode;
    } 

    public function afterAuthorize(\ParadoxLabs\TokenBase\Model\AbstractMethod $subject, $result, \Magento\Payment\Model\InfoInterface $payment)
    {
        $lastResponse = $result->gateway()->getLastResponse();

        $data = array(
            'order_id' => $payment->getOrder()->getIncrementId(),
            'card_type' => $payment->getOrder()->getPayment()->getCcType(),
            'authorization_code' => $lastResponse["transactionResponse"]['authCode']
        );
        $this->transactionAuthCode->setData($data);
        $this->transactionAuthCode->save();
        
        return $result;
    }
}
