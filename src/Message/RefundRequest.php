<?php

namespace Omnipay\Emp\Message;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class RefundRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'https://my.emerchantpay.com/service/order/credit';
    }

    /**
     * @param  mixed $data
     * @return \Omnipay\Emp\Message\RefundResponse
     */
    public function sendData($data)
    {
        $responseData = parent::sendData($data);

        $this->response = new RefundResponse($this, $responseData);

        return $this->response;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        $this->validate('transactionReference', 'transactionId');

        $items = $this->getItems();

        if ($items) {
            foreach ($items as $index => $item) {

                $i = $index + 1;

                $data["item_{$i}_id"] = $item->getName();
                $data["item_{$i}_amount"] = $item->getPrice();
            }
        } else {
            $this->validate('amount');

            $data['amount'] = $this->getAmount();
        }

        $data['reason'] = $this->getDescription();
        $data['order_id'] = $this->getTransactionReference();
        $data['trans_id'] = $this->getTransactionId();

        return $data;
    }
}
