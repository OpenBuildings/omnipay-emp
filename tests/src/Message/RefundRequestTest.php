<?php

namespace Omnipay\Emp\Test\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Message\RefundRequest;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Message\RefundRequest
 */
class RefundRequestTest extends TestCase
{
    private $request;
    private $requestData = array(
        'order_id' => '51697014',
            'order_total' => '10.00',
            'test_transaction' => '1',
            'order_datetime' => '2014-06-11 18:09:24',
            'order_status' => 'Paid',
            'cart' => array(
            'item' => array(
                array(
                    'id' => '51931344',
                    'code' => '10',
                    'name' => 'Product 1 Desc',
                    'description' => array(),
                    'qty' => '2',
                    'digital' => '0',
                    'discount' => '0',
                    'predefined' => '0',
                    'unit_price' => '5.00',
                ),
                array(
                    'id' => '51931354',
                    'code' => '12',
                    'name' => 'Shipping for Product 1',
                    'description' =>array(),
                    'qty' => '1',
                    'digital' => '0',
                    'discount' => '0',
                    'predefined' => '0',
                    'unit_price' => '5.00',
                ),
                array(
                    'id' => '51931364',
                    'code' => '13',
                    'name' => 'Promotion',
                    'description' =>array(),
                    'qty' => '1',
                    'digital' => '0',
                    'discount' => '1',
                    'predefined' => '0',
                    'unit_price' => '-5.00',
                ),
            ),
        ),
        'transaction' => array(
            'type' => 'sale',
            'response' => 'A',
            'response_code' => 'OP000',
            'response_text' => 'ApproveTEST',
            'trans_id' => '1413907284',
            'account_id' => '635182',
        ),
    );

    private $requestData2 = array(
        'order_id' => '143118424',
        'order_total' => '141.24',
        'test_transaction' => '1',
        'order_datetime' => '2015-05-26 09:21:31',
        'order_status' => 'Paid',
        'cart' => array(
            'item' => array(
                'id' => '143637224',
                'code' => '4F0R7U',
                'name' => 'products from vitamin',
                'description' => array(),
                'qty' => '1',
                'digital' => '0',
                'discount' => '0',
                'predefined' => '0',
                'unit_price' => '141.24',
            )
        ),
        'transaction' => array(
            'type' => 'sale',
            'response' => 'A',
            'response_code' => 'OP000',
            'response_text' => 'ApproveTEST',
            'trans_id' => '1849280744',
            'account_id' => '635182',
        )
    );

    public function setUp()
    {
        parent::setUp();

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'items' => array(
                array(
                    'name' => '51945994',
                    'price' => '10.00',
                ),
                array(
                    'name' => '51946004',
                    'price' => '5.00',
                )
            ),
            'description' => 'Faulty Product',
            'transactionId' => '51711614',
            'transactionReference' => '1413980404',
        ));
    }

    public function providerGetData()
    {
        return array(
            // Iteration 0
            array(
                // Data
                array(
                    'clientId' => 'id1',
                    'apiKey' => 'key1',
                    'items' => array(
                        array(
                            'name' => '10',
                            'price' => '10.00',
                        ),
                        array(
                            'name' => '12',
                            'price' => '5.00',
                        )
                    ),
                    'requestData' => $this->requestData,
                    'description' => 'Faulty Product',
                    'transactionId' => '51711614',
                    'transactionReference' => '1413980404',
                ),
                // Expected
                array(
                    'client_id' => 'id1',
                    'api_key' => 'key1',
                    'item_1_id' => '51931344',
                    'item_1_amount' => '10.00',
                    'item_2_id' => '51931354',
                    'item_2_amount' => '5.00',
                    'reason' => 'Faulty Product',
                    'order_id' => '51711614',
                    'trans_id' => '1413980404',
                )
            ),

            // Iteration 1
            array(
                // Data
                array(
                    'clientId' => 'id1',
                    'apiKey' => 'key1',
                    'amount' => '200.00',
                    'description' => 'Faulty Product',
                    'transactionId' => '51711614',
                    'transactionReference' => '1413980404',
                ),
                // Expected
                array(
                    'client_id' => 'id1',
                    'api_key' => 'key1',
                    'amount' => '200.00',
                    'reason' => 'Faulty Product',
                    'order_id' => '51711614',
                    'trans_id' => '1413980404',
                )
            )
        );
    }

    /**
     * @covers ::setRequestData
     * @covers ::getRequestData
     */
    public function testRequestData()
    {
        $this->assertSame($this->request, $this->request->setRequestData($this->requestData));
        $this->assertSame($this->requestData, $this->request->getRequestData());
    }

    /**
     * @covers ::getRequestDataItem
     */
    public function testGetRequestDataItem()
    {
        $this->request->setRequestData($this->requestData);

        $this->assertEquals('51931344', $this->request->getRequestDataItem(10));
        $this->assertEquals('51931354', $this->request->getRequestDataItem(12));
        $this->assertEquals('51931364', $this->request->getRequestDataItem(13));

        $this->request->setRequestData($this->requestData2);

        $this->assertEquals('143637224', $this->request->getRequestDataItem('4F0R7U'));
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
        $this->setMockHttpResponse('RefundFailure.http');

        $response = $this->request->sendData(array('custom' => 'data'));

        $this->assertInstanceOf('Omnipay\Emp\Message\RefundResponse', $response);
        $this->assertSame($response, $this->request->getResponse());
    }

    /**
     * @covers ::getEndpoint
     */
    public function testGetEndpoint()
    {
        $this->assertStringEndsWith('order/credit', $this->request->getEndpoint());
    }
}
