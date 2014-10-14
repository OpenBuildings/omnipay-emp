<?php

namespace Omnipay\Emp\Message;

use Omnipay\Emp\Threatmetrix;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    /**
     * @return string
     */
    abstract function getEndpoint();

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    /**
     * @param string $value
     */
    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->getParameter('apiKey');
    }

    /**
     * @param string $value
     */
    public function setApiKey($value)
    {
        return $this->setParameter('apiKey', $value);
    }

    /**
     * @return string
     */
    public function getProxy()
    {
        return $this->getParameter('proxy');
    }

    /**
     * @param string $value
     */
    public function setProxy($value)
    {
        return $this->setParameter('proxy', $value);
    }

    /**
     * @return Threatmetrix
     */
    public function getThreatmetrix()
    {
        return $this->getParameter('threatmetrix');
    }

    /**
     * @param Threatmetrix $value
     */
    public function setThreatmetrix(Threatmetrix $value)
    {
        return $this->setParameter('threatmetrix', $value);
    }

    /**
     * Base "getData" should be extended by other requests
     *
     * @return array
     */
    public function getData()
    {
        $data = array();

        if ($this->getTestMode()) {
            $data['test_transaction'] = '1';
        }

        if ($this->getThreatmetrix()) {
            $data['thm_session_id'] = $this->getThreatmetrix()->getSessionId();
        }

        $data['client_id'] = $this->getClientId();
        $data['api_key'] = $this->getApiKey();

        return $data;
    }

    /**
     * @param  mixed $data
     * @return array
     */
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
