<?php

namespace Omnipay\Emp;

use Omnipay\Common\AbstractGateway;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Gateway extends AbstractGateway
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'eMerchantPay';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'apiKey' => '',
        );
    }

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
     * Set a proxy through which to pass all the requests.
     * E.g. usr:pass@www.example.com:8888
     *
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
     * @param Threatmetrix $threatmetrix
     */
    public function setThreatmetrix(Threatmetrix $threatmetrix)
    {
        return $this->setParameter('threatmetrix', $threatmetrix);
    }

    /**
     * @param  array  $parameters
     * @return Omnipay\Emp\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest(__NAMESPACE__.'\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param  array  $parameters
     * @return Omnipay\Emp\Message\RefundRequest
     */
    public function refund(array $parameters = array())
    {
        return $this->createRequest(__NAMESPACE__.'\Message\RefundRequest', $parameters);
    }
}
