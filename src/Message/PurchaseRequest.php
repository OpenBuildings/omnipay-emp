<?php

namespace Omnipay\Emp\Message;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getEndpoint()
    {
        return 'https://my.emerchantpay.com/service/order/submit';
    }

    /**
     * @param  mixed $data
     * @return \Omnipay\Emp\Message\PurchaseResponse
     */
    public function sendData($data)
    {
        $responseData = parent::sendData($data);

        $this->response = new PurchaseResponse($this, $responseData);

        return $this->response;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = parent::getData();

        $this->validate('transactionReference', 'currency', 'clientIp', 'card', 'items');

        $card = $this->getCard();
        $items = $this->getItems();
        $currency = $this->getCurrency();

        $data['customer_first_name']    = $card->getFirstName();
        $data['customer_last_name']     = $card->getLastName();
        $data['customer_address']       = $card->getAddress1();
        $data['customer_address2']      = $card->getAddress2();
        $data['customer_city']          = $card->getCity();
        $data['customer_country']       = $card->getCountry();
        $data['customer_postcode']      = $card->getPostcode();
        $data['customer_email']         = $card->getEmail();
        $data['customer_phone']         = $card->getPhone();

        $data['card_holder_name']       = $card->getName();
        $data['card_number']            = $card->getNumber();
        $data['exp_month']              = str_pad($card->getExpiryMonth(), 2, '0', STR_PAD_LEFT);
        $data['exp_year']               = substr($card->getExpiryYear(), 2);
        $data['cvv']                    = $card->getCvv();

        $data['order_reference']        = $this->getTransactionReference();
        $data['order_currency']         = $currency;
        $data['payment_method']         = 'creditcard';
        $data['credit_card_trans_type'] = 'sale';
        $data['ip_address']             = $this->getClientIp();

        foreach ($items as $index => $item) {

            $i = $index + 1;

            $data["item_{$i}_predefined"]             = '0';
            $data["item_{$i}_digital"]                = '0';
            $data["item_{$i}_code"]                   = $item->getName();
            $data["item_{$i}_qty"]                    = $item->getQuantity();
            $data["item_{$i}_discount"]               = $item->getPrice() < 0 ? '1' : '0';
            $data["item_{$i}_name"]                   = $item->getDescription();
            $data["item_{$i}_unit_price_{$currency}"] = $item->getPrice();
        }

        if ($this->getThreatmetrix()) {
            $data['thm_session_id'] = $this->getThreatmetrix()->getSessionId();
        }

        return $data;
    }
}
