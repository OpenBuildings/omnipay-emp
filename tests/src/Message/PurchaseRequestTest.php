<?php

namespace Omnipay\Emp\Test\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Message\PurchaseRequest;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Message\PurchaseRequest
 */
class PurchaseRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
    }

    public function providerGetData()
    {
        return array(
            array(
                // Data
                array(
                    'clientId' => 'id1',
                    'apiKey' => 'key1',
                    'currency' => 'GBP',
                    'transactionReference' => 'referenceID1',
                    'clientIp' => '95.87.212.88',
                    'items' => array(
                        array(
                            'name' => 10,
                            'price' => '5.00',
                            'description' => 'Product 1 Desc',
                            'quantity' => 2
                        ),
                        array(
                            'name' => 12,
                            'price' => '5.00',
                            'description' => 'Shipping for Product 1',
                            'quantity' => 1
                        ),
                        array(
                            'name' => 12,
                            'price' => '0.00',
                            'description' => 'Promotion',
                            'quantity' => 1
                        ),
                    ),
                    'card' => array(
                        'firstName' => 'Example',
                        'lastName' => 'User',
                        'number' => '4111111111111111',
                        'expiryMonth' => 7,
                        'expiryYear' => 2013,
                        'cvv' => 123,
                        'address1' => '123 Shipping St',
                        'address2' => 'Shipsville',
                        'city' => 'Shipstown',
                        'postcode' => '54321',
                        'state' => 'NY',
                        'country' => 'US',
                        'phone' => '(555) 987-6543',
                        'email' => 'john@example.com',
                    )
                ),

                // Expected
                array(
                    'client_id' => 'id1',
                    'api_key' => 'key1',
                    'customer_first_name' => 'Example',
                    'customer_last_name' => 'User',
                    'customer_address' => '123 Shipping St',
                    'customer_address2' => 'Shipsville',
                    'customer_city' => 'Shipstown',
                    'customer_country' => 'US',
                    'customer_postcode' => '54321',
                    'customer_email' => 'john@example.com',
                    'customer_phone' => '(555) 987-6543',
                    'card_holder_name' => 'Example User',
                    'card_number' => '4111111111111111',
                    'exp_month' => '07',
                    'exp_year' => '13',
                    'cvv' => 123,
                    'order_reference' => 'referenceID1',
                    'order_currency' => 'GBP',
                    'payment_method' => 'creditcard',
                    'credit_card_trans_type' => 'sale',
                    'ip_address' => '95.87.212.88',
                    'item_1_predefined' => '0',
                    'item_1_digital' => '0',
                    'item_1_code' => 10,
                    'item_1_qty' => 2,
                    'item_1_discount' => '0',
                    'item_1_name' => 'Product 1 Desc',
                    'item_1_unit_price_GBP' => '5.00',
                    'item_2_predefined' => '0',
                    'item_2_digital' => '0',
                    'item_2_code' => 12,
                    'item_2_qty' => 1,
                    'item_2_discount' => '0',
                    'item_2_name' => 'Shipping for Product 1',
                    'item_2_unit_price_GBP' => '5.00',
                    'item_3_predefined' => '0',
                    'item_3_digital' => '0',
                    'item_3_code' => 12,
                    'item_3_qty' => 1,
                    'item_3_discount' => '0',
                    'item_3_name' => 'Promotion',
                    'item_3_unit_price_GBP' => '0.00',
                )
            )
        );
    }

    /**
     * @dataProvider providerGetData
     * @covers ::getData
     */
    public function testGetData($data, $expected)
    {
        $this->request->initialize($data);
        $result = $this->request->getData();

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers ::sendData
     */
    public function testSendData()
    {
        $this->setMockHttpResponse('PurchaseFailure.http');

        $response = $this->request->sendData(array('custom' => 'data'));

        $this->assertInstanceOf('Omnipay\Emp\Message\PurchaseResponse', $response);
        $this->assertSame($response, $this->request->getResponse());
    }

    /**
     * @covers ::getEndpoint
     */
    public function testGetEndpoint()
    {
        $this->assertStringEndsWith('order/submit', $this->request->getEndpoint());
    }
}
