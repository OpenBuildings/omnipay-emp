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
     * @return Threatmatrix
     */
    public function getThreatmatrix()
    {
        return $this->getParameter('threatmatrix');
    }

    /**
     * @param Threatmatrix $value
     */
    public function setThreatmatrix(Threatmatrix $value)
    {
        return $this->setParameter('threatmatrix', $value);
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

        if ($this->getThreatmatrix()) {
            $data['thm_session_id'] = $this->getThreatmatrix()->getSessionId();
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
