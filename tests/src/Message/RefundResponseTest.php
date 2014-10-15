<?php

namespace Omnipay\Emp\Test\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Message\RefundRequest;
use Omnipay\Emp\Message\RefundResponse;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Message\RefundResponse
 */
class RefundResponseTest extends TestCase
{
    private $request;
    private $dataSuccess;
    private $dataFailure;

    public function setUp()
    {
        parent::setUp();

        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->dataSuccess = array(
            'response' => 'A',
            'responsecode' => 'OP000',
            'responsetext' => 'ApproveTEST',
            'trans_id' => '1413976984',
        );

        $this->dataFailure = array(
            'responsetext' => 'Invalid order_id',
            'responsecode' => 'OP299',
            'response' => 'E',
        );
    }

    /**
     * @covers ::isSuccessful
     */
    public function testIsSuccessfulSuccess()
    {
        $response = new RefundResponse($this->request, $this->dataSuccess);

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @covers ::isSuccessful
     */
    public function testIsSuccessfulFailure()
    {
        $response = new RefundResponse($this->request, $this->dataFailure);

        $this->assertFalse($response->isSuccessful());
    }

    /**
     * @covers ::getTransactionReference
     */
    public function testGetTransactionReferenceSuccess()
    {
        $response = new RefundResponse($this->request, $this->dataSuccess);

        $this->assertEquals('1413976984', $response->getTransactionReference());
    }

    /**
     * @covers ::getTransactionReference
     */
    public function testGetTransactionReferenceFailure()
    {
        $response = new RefundResponse($this->request, $this->dataFailure);

        $this->assertNull($response->getTransactionReference());
    }

    /**
     * @covers ::getResponse
     */
    public function testGetResponseSuccess()
    {
        $response = new RefundResponse($this->request, $this->dataSuccess);

        $this->assertEquals('A', $response->getResponse());
    }

    /**
     * @covers ::getResponse
     */
    public function testGetResponseFailure()
    {
        $response = new RefundResponse($this->request, $this->dataFailure);

        $this->assertEquals('E', $response->getResponse());
    }

    /**
     * @covers ::getResponse
     */
    public function testGetResponseError()
    {
        $response = new RefundResponse($this->request, array());

        $this->assertNull($response->getResponse());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeSuccess()
    {
        $response = new RefundResponse($this->request, $this->dataSuccess);

        $this->assertEquals('OP000', $response->getCode());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeFailure()
    {
        $response = new RefundResponse($this->request, $this->dataFailure);

        $this->assertEquals('OP299', $response->getCode());
    }

    /**
     * @covers ::getCode
     */
    public function testGetCodeError()
    {
        $response = new RefundResponse($this->request, array());

        $this->assertNull($response->getCode());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageSuccess()
    {
        $response = new RefundResponse($this->request, $this->dataSuccess);

        $this->assertEquals('ApproveTEST', $response->getMessage());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageFailure()
    {
        $response = new RefundResponse($this->request, $this->dataFailure);

        $this->assertEquals('Invalid order_id', $response->getMessage());
    }

    /**
     * @covers ::getMessage
     */
    public function testGetMessageError()
    {
        $response = new RefundResponse($this->request, array());

        $this->assertNull($response->getMessage());
    }
}
