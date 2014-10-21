<?php

namespace Omnipay\Emp\Test\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Message\PurchaseRequest;
use Omnipay\Emp\Message\PurchaseResponse;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Message\PurchaseResponse
 */
class PurchaseResponseTest extends TestCase
{
    private $request;
    private $dataSuccess;
    private $dataFailure;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->dataSuccess = array(
            'order_id' => '51711614',
            'order_total' => '15.00',
            'test_transaction' => '1',
            'order_datetime' => '2014-06-11 19:43:39',
            'order_status' => 'Paid',
            'cart' => array(
              'item' => array(
                array(
                  'id' => '51945994',
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
                  'id' => '51946004',
                  'code' => '12',
                  'name' => 'Shipping for Product 1',
                  'description' => array(),
                  'qty' => '1',
                  'digital' => '0',
                  'discount' => '0',
                  'predefined' => '0',
                  'unit_price' => '5.00',
                ),
                array(
                  'id' => '51946014',
                  'code' => '12',
                  'name' => 'Promotion',
                  'description' => array(),
                  'qty' => '1',
                  'digital' => '0',
                  'discount' => '0',
                  'predefined' => '0',
                  'unit_price' => '0.00',
                ),
              ),
            ),
            'transaction' => array(
              'type' => 'sale',
              'response' => 'A',
              'response_code' => 'OP000',
              'response_text' => 'ApproveTEST',
              'trans_id' => '1413980404',
              'account_id' => '635182',
            ),
        );

        $this->dataFailure = array(
            'errors' => array(
              'error' => array(
                'code' => 'OP812',
                'text' => 'Card expired',
              ),
            ),
        );
    }

    /**
     * @covers ::isSuccessful
     */
    public function testIsSuccessfulSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers ::isSuccessful
     */
    public function testIsSuccessfulFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @covers ::getTransactionId
     */
    public function testGetTransactionIdSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertEquals('51711614', $response->getTransactionId());
    }

    /**
     * @covers ::getTransactionId
     */
    public function testGetTransactionIdFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertNull($response->getTransactionId());
    }

    /**
     * @covers ::getTransactionReference
     */
    public function testGetTransactionReferenceSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertEquals('1413980404', $response->getTransactionReference());
    }

    /**
     * @covers ::getTransactionReference
     */
    public function testGetTransactionReferenceFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertNull($response->getTransactionReference());
    }

    /**
     * @covers ::getTransactionResponse
     */
    public function testGetTransactionResponseSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertEquals('A', $response->getTransactionResponse());
    }

    /**
     * @covers ::getTransactionResponse
     */
    public function testGetTransactionResponseFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertNull($response->getTransactionResponse());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertEquals('OP000', $response->getCode());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertEquals('OP812', $response->getCode());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeError()
    {
        $response = new PurchaseResponse($this->request, array());

        $this->assertNull($response->getCode());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertEquals('ApproveTEST', $response->getMessage());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertEquals('Card expired (OP812)', $response->getMessage());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageError()
    {
        $response = new PurchaseResponse($this->request, array());

        $this->assertNull($response->getMessage());
    }

    /**
     * @covers ::getErrors
     */
    public function testGetErrorsSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertNull($response->getErrors());
    }

    /**
     * @covers ::getErrors
     */
    public function testGetErrorsFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $expected = array(
            array('code' => 'OP812', 'text' => 'Card expired'),
        );

        $this->assertEquals($expected, $response->getErrors());
    }

    /**
     * @covers ::getErrorMessage
     */
    public function testGetErrorMessageSuccess()
    {
        $response = new PurchaseResponse($this->request, $this->dataSuccess);

        $this->assertNull($response->getErrorMessage());
    }

    /**
     * @covers ::getErrorMessage
     */
    public function testGetErrorMessageFailure()
    {
        $response = new PurchaseResponse($this->request, $this->dataFailure);

        $this->assertEquals('Card expired (OP812)', $response->getErrorMessage());
    }
}
