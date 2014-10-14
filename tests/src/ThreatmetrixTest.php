<?php

namespace Omnipay\Emp\Test;

use Omnipay\Tests\TestCase;
use Omnipay\Emp\Threatmetrix;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 *
 * @coversDefaultClass Omnipay\Emp\Threatmetrix
 */
class ThreatmetrixTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getOrganizationId
     * @covers ::getSessionId
     * @covers ::getSession
     */
    public function testConstruct()
    {
        $tmx = new Threatmetrix('myorg1', 'client-id-2');
        $this->assertEquals('myorg1', $tmx->getOrganizationId());
        $this->assertContains('client-id-2', $tmx->getSessionId());
        $this->assertNotNull($tmx->getSession());
    }

    /**
     * @covers ::getUrlQuery
     */
    public function testGetUrlQuery()
    {
        $tmx = new Threatmetrix('myorg3', 'client-id-4');

        $params = array();
        parse_str($tmx->getUrlQuery(), $params);

        $this->assertEquals('myorg3', $params['org_id']);
        $this->assertEquals($tmx->getSessionId(), $params['session_id']);
    }

    /**
     * @covers ::getTrackingUrl
     */
    public function testGetTrackingUrl()
    {
        $instance = new Threatmetrix('myorg3', 'client-id-4');

        $url = $instance->getTrackingUrl();

        $this->assertContains($instance->getTrackingUrl(), $url);
    }

    /**
     * @covers ::getTrackingCode
     */
    public function testGetTrackingCode()
    {
        $instance = new Threatmetrix('myorg3', 'client-id-4');

        $code = $instance->getTrackingCode();

        $this->assertSelectCount('img', 1, $code);
        $this->assertSelectCount('object', 1, $code);
        $this->assertSelectCount('script', 1, $code);

        $this->assertContains($instance->getUrlQuery(), $code);
    }
}
