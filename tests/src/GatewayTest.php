<?php

namespace Omnipay\Emp\Test;

use Omnipay\Tests\GatewayTestCase;
use Omnipay\Emp\Gateway;
use Omnipay\Emp\Threatmetrix;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Gateway
 */
class GatewayTest extends GatewayTestCase
{
    protected $gateway;
    private $purchaseOptions;
    private $refundOptions;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setClientId(getenv('EMP_CID'));
        $this->gateway->setApiKey(getenv('EMP_KEY'));
        $this->gateway->setProxy(getenv('EMP_PROXY'));
        $this->gateway->setTestMode(true);

        $this->purchaseOptions = array(
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
        );

        $this->refundOptions = array(
            'amount' => '20.00',
            'description' => 'Faulty Product',
            'transactionReference' => '51711614',
            'transactionId' => '1413980404',
        );
    }

    /**
     * @covers ::getApiKey
     * @covers ::setApiKey
     */
    public function testApiKey()
    {
        $this->assertSame($this->gateway, $this->gateway->setApiKey('abc123'));
        $this->assertSame('abc123', $this->gateway->getApiKey());
    }

    /**
     * @covers ::getClientId
     * @covers ::setClientId
     */
    public function testClientId()
    {
        $this->assertSame($this->gateway, $this->gateway->setClientId('abc123'));
        $this->assertSame('abc123', $this->gateway->getClientId());
    }

    /**
     * @covers ::getProxy
     * @covers ::setProxy
     */
    public function testProxy()
    {
        $this->assertSame($this->gateway, $this->gateway->setProxy('abc123'));
        $this->assertSame('abc123', $this->gateway->getProxy());
    }

    /**
     * @covers ::getThreatmetrix
     * @covers ::setThreatmetrix
     */
    public function testThreatmetrix()
    {
        $threatmetrix = new Threatmetrix('asd123', 'asd123');
        $this->assertSame($this->gateway, $this->gateway->setThreatmetrix($threatmetrix));
        $this->assertSame($threatmetrix, $this->gateway->getThreatmetrix());
    }

    /**
     * @covers ::purchase
     */
    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.http');

        $request = $this->gateway->purchase($this->purchaseOptions);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\Emp\Message\PurchaseRequest', $request);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1413907284', $response->getTransactionReference());
        $this->assertSame('OP000', $response->getCode());
        $this->assertSame('51697014', $response->getTransactionId());
        $this->assertEquals('ApproveTEST', $response->getMessage());
    }

    /**
     * @covers ::purchase
     */
    public function testPurchaseFailure()
    {
        $this->setMockHttpResponse('PurchaseFailure.http');

        $request = $this->gateway->purchase($this->purchaseOptions);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\Emp\Message\PurchaseRequest', $request);
        $this->assertInstanceOf('Omnipay\Emp\Message\PurchaseResponse', $response);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('OP858', $response->getCode());
        $this->assertNull($response->getTransactionId());
        $this->assertEquals('customer_email is required (OP858), invalid ipaddress (OP828)', $response->getMessage());
    }

    /**
     * @covers ::refund
     */
    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.http');

        $request = $this->gateway->refund($this->refundOptions);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\Emp\Message\RefundRequest', $request);
        $this->assertInstanceOf('Omnipay\Emp\Message\RefundResponse', $response);

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('1413976984', $response->getTransactionReference());
        $this->assertEquals('A', $response->getResponse());
        $this->assertSame('OP000', $response->getCode());
        $this->assertSame('ApproveTEST', $response->getMessage());
    }

    /**
     * @covers ::refund
     */
    public function testRefundFailure()
    {
        $this->setMockHttpResponse('RefundFailure.http');

        $request = $this->gateway->refund($this->refundOptions);

        $response = $request->send();

        $this->assertInstanceOf('Omnipay\Emp\Message\RefundRequest', $request);
        $this->assertInstanceOf('Omnipay\Emp\Message\RefundResponse', $response);

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertEquals('E', $response->getResponse());
        $this->assertSame('OP299', $response->getCode());
        $this->assertSame('Invalid order_id', $response->getMessage());
    }
}
