<?php

namespace FoxChapel\AcumenIntegration\Plugin;

class AuthorizeResponse
{
    public function afterAuthorize(\ParadoxLabs\TokenBase\Model\AbstractMethod $subject, $result)
    {
        $lastResponse = $result->gateway()->getLastResponse();

        return $result;
    }
}
