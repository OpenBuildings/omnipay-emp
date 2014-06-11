<?php

namespace Omnipay\Emp\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class PurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return ($this->getTransactionResponse() === 'A' and ! $this->getErrors());
    }

    public function getErrors()
    {
        if (isset($this->data['errors']['error'])) {
            $errors = $this->data['errors']['error'];

            return isset($errors['code']) ? array($errors) : $errors;
        }
    }

    public function getErrorMessage()
    {
        $errors = $this->getErrors();

        if ($errors) {
            return join(', ', array_map(function ($item) {
                return "{$item['text']} ({$item['code']})";
            }, $errors));
        }
    }

    public function getTransactionId()
    {
        if (isset($this->data['transaction']['trans_id'])) {
            return $this->data['transaction']['trans_id'];
        }
    }

    public function getTransactionReference()
    {
        if (isset($this->data['order_id'])) {
            return $this->data['order_id'];
        }
    }

    public function getTransactionResponse()
    {
        if (isset($this->data['transaction']['response'])) {
            return $this->data['transaction']['response'];
        }
    }

    public function getCode()
    {
        $errors = $this->getErrors();

        if ($errors) {
            return $errors[0]['code'];
        }

        if (isset($this->data['transaction']['response_code'])) {
            return $this->data['transaction']['response_code'];
        }
    }

    public function getMessage()
    {
        if ($this->getErrors()) {
            return $this->getErrorMessage();
        }

        if (isset($this->data['transaction']['response_text'])) {
            return $this->data['transaction']['response_text'];
        }
    }
}
