<?php

namespace Omnipay\Emp\Message;

use Omnipay\Emp\Threatmatrix;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    abstract function getEndpoint();

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    public function getProxy()
    {
        return $this->getParameter('proxy');
    }

    public function setProxy($value)
    {
        return $this->setParameter('proxy', $value);
    }

    public function getThreatmatrix()
    {
        return $this->getParameter('threatmatrix');
    }

    public function setThreatmatrix(Threatmatrix $value)
    {
        return $this->setParameter('threatmatrix', $value);
    }

    public function getData()
    {
        $data = array();

        if ($this->getTestMode()) {
            $data['test_transaction'] = '1';
        }

        if ($this->getThreatmatrix()) {
            $data['thm_session_id'] = $this->getThreatmatrix()->getSessionId();
        }

        $data['client_id'] = $this->getClientId();
        $data['api_key'] = $this->getApiKey();

        return $data;
    }

    public function sendData($data)
    {
        $httpRequest = $this->httpClient->post(
            $this->getEndpoint(),
            null,
            $data,
            $this->getProxy() ? array('proxy' => $this->getProxy()) : array()
        );

        $httpResponse = $httpRequest->send();

        return json_decode(json_encode($httpResponse->xml()), true);
    }
}
