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
            'transactionReference' => '51711614',
            'transactionId' => '1413980404',
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
                            'name' => '51945994',
                            'price' => '10.00',
                        ),
                        array(
                            'name' => '51946004',
                            'price' => '5.00',
                        )
                    ),
                    'description' => 'Faulty Product',
                    'transactionReference' => '51711614',
                    'transactionId' => '1413980404',
                ),
                // Expected
                array(
                    'client_id' => 'id1',
                    'api_key' => 'key1',
                    'item_1_id' => '51945994',
                    'item_1_amount' => '10.00',
                    'item_2_id' => '51946004',
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
                    'transactionReference' => '51711614',
                    'transactionId' => '1413980404',
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
