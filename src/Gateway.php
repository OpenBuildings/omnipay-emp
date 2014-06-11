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
    public function getName()
    {
        return 'eMerchantPay';
    }

    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'apiKey' => '',
        );
    }

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

    public function setThreatmatrix(Threatmatrix $threatmatrix)
    {
        return $this->setParameter('threatmatrix', $threatmatrix);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(__NAMESPACE__.'\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(__NAMESPACE__.'\Message\RefundRequest', $parameters);
    }
}
