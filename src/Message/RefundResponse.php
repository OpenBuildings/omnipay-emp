<?php

namespace Omnipay\Emp\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundResponse extends AbstractResponse
{
    /**
     * @return boolean
     */
    public function isSuccessful()
    {
        return ($this->getResponse() === 'A');
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['trans_id'])) {
            return $this->data['trans_id'];
        }
    }

    /**
     * @return string|null
     */
    public function getResponse()
    {
        if (isset($this->data['response'])) {
            return $this->data['response'];
        }
    }

    /**
     * @return string|null
     */
    public function getCode()
    {
        if (isset($this->data['responsecode'])) {
            return $this->data['responsecode'];
        }
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        if (isset($this->data['responsetext'])) {
            return $this->data['responsetext'];
        }
    }
}
