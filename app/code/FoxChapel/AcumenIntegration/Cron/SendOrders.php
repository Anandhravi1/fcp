<?php

namespace FoxChapel\AcumenIntegration\Cron;

use FoxChapel\AcumenIntegration\Model\SendOrdersAsXmlInEmail;

/**
 * Send Orders Emails for Acumen Integration.
 *
 * Performs creating Orders information in XML and sending to configured 
 * for Acumen Integration.
 */
class SendOrders
{
    /**
     *
     * @var \FoxChapel\AcumenIntegration\Model\SendOrdersAsXmlInEmail
     */
    protected $sendOrdersAsXmlInEmail;

    /**
     * @param \FoxChapel\AcumenIntegration\Model\SendOrdersAsXmlInEmail $sendOrdersAsXmlInEmail
     */
    public function __construct(SendOrdersAsXmlInEmail $sendOrdersAsXmlInEmail)
    {
        $this->sendOrdersAsXmlInEmail = $sendOrdersAsXmlInEmail;
    }

    /**
     *
     * @return void
     */
    public function execute()
    {
        $this->sendOrdersAsXmlInEmail->sendOrders();
    }
}
