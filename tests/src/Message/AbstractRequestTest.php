<?php

namespace Omnipay\Emp\Test\Message;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Message\AbstractRequest;
use Omnipay\Emp\Threatmetrix;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Message\AbstractRequest
 */
class AbstractRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        $this->request = $this->getMockForAbstractClass(
            'Omnipay\Emp\Message\AbstractRequest',
            array($this->getHttpClient(), $this->getHttpRequest())
        );

        $this->request->initialize();
    }

    /**
     * @covers ::getApiKey
     * @covers ::setApiKey
     */
    public function testApiKey()
    {
        $this->assertSame($this->request, $this->request->setApiKey('abc123'));
        $this->assertSame('abc123', $this->request->getApiKey());
    }

    /**
     * @covers ::getClientId
     * @covers ::setClientId
     */
    public function testClientId()
    {
        $this->assertSame($this->request, $this->request->setClientId('abc123'));
        $this->assertSame('abc123', $this->request->getClientId());
    }

    /**
     * @covers ::getProxy
     * @covers ::setProxy
     */
    public function testProxy()
    {
        $this->assertSame($this->request, $this->request->setProxy('abc123'));
        $this->assertSame('abc123', $this->request->getProxy());
    }

    /**
     * @covers ::getThreatmetrix
     * @covers ::setThreatmetrix
     */
    public function testThreatmetrix()
    {
        $threatmetrix = new Threatmetrix('asd123', 'asd123');
        $this->assertSame($this->request, $this->request->setThreatmetrix($threatmetrix));
        $this->assertSame($threatmetrix, $this->request->getThreatmetrix());
    }

    public function providerGetData()
    {
        $tmx = $this->getMock('Omnipay\Emp\Threatmetrix', array('getSessionId'), array('sID', 'orgID'));
        $tmx
            ->expects($this->once())
            ->method('getSessionId')
            ->will($this->returnValue('uniqueSessionID1'));

        return array(
            array(
                array(
                    'apiKey' => 'some key 1',
                    'clientId' => 'some id 1',
                    'testMode' => true,
                    'threatmetrix' => $tmx,
                ),
                array(
                    'test_transaction' => '1',
                    'thm_session_id' => 'uniqueSessionID1',
                    'client_id' => 'some id 1',
                    'api_key' => 'some key 1',
                ),
            ),
            array(
                array(
                    'apiKey' => 'some key 1',
                    'clientId' => 'some id 1',
                ),
                array(
                    'client_id' => 'some id 1',
                    'api_key' => 'some key 1',
                ),
            ),
        );
    }

    /**
     * @covers ::getData
     * @dataProvider providerGetData
     */
    public function testGetData($data, $expected)
    {
        $this->request->initialize($data);

        $result = $this->request->getData();

        $this->assertSame($expected, $result);
    }

    /**
     * @covers ::sendData
     */
    public function testSendData()
    {
        $this->setMockHttpResponse('RefundSuccess.http');

        $response = $this->request->sendData(array('some' => 'data'));

        $expected = array(
            'response' => 'A',
            'responsecode' => 'OP000',
            'responsetext' => 'ApproveTEST',
            'trans_id' => '1413976984',
        );

        $this->assertSame($expected, $response);

        $this->setMockHttpResponse('RefundFailure.http');

        $this->request->setProxy('testproxy');

        $response = $this->request->sendData(array('some' => 'data'));

        $expected = array(
            'responsetext' => 'Invalid order_id',
            'responsecode' => 'OP299',
            'response' => 'E',
        );

        $this->assertSame($expected, $response);
    }
}
