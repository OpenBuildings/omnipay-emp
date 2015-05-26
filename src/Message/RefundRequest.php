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
     * @return array
     */
    public function getRequestData()
    {
        return $this->getParameter('requestData');
    }

    /**
     * @param array $value
     */
    public function setRequestData(array $value)
    {
        return $this->setParameter('requestData', $value);
    }

    /**
     * @param  string $code
     * @return string
     */
    public function getRequestDataItem($code)
    {
        $data = $this->getRequestData();

        if (isset($data['cart']['item'])) {
            $items = $data['cart']['item'];

            if (isset($items['code']) and $items['code'] == $code) {
                return $items['id'];
            }

            foreach ($items as $item) {
                if (isset($item['code']) and $item['code'] == $code) {
                    return $item['id'];
                }
            }
        }

        return $code;
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
            $this->validate('requestData');

            foreach ($items as $index => $item) {

                $i = $index + 1;

                $data["item_{$i}_id"] = $this->getRequestDataItem($item->getName());
                $data["item_{$i}_amount"] = $item->getPrice();
            }
        } else {
            $this->validate('amount');

            $data['amount'] = $this->getAmount();
        }

        $data['reason'] = $this->getDescription();
        $data['order_id'] = $this->getTransactionId();
        $data['trans_id'] = $this->getTransactionReference();

        return $data;
    }
}
