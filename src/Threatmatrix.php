<?php

namespace Omnipay\Emp;

/**
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright 2014, Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class Threatmatrix
{
    private $organizationId;
    private $sessionId;
    private $session;

    public function __construct($organizationId, $clientId)
    {
        $this->organizationId = $organizationId;
        $this->sessionId = $clientId.date('Ymdhis').rand(100000,999999);;
        $this->session = md5(rand());
    }

    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getUrlQuery()
    {
        return http_build_query(array(
            'org_id' => $this->organizationId,
            'session_id' => $this->sessionId,
        ));
    }

    /**
     * Return the tracking code, that has to be placed to the page where the payment form is
     * @return string
     */
    public function getTrackingCode()
    {
        $urlQuery = $this->getUrlQuery();

        return <<<TRACKING
<div style="position:absolute;left:0;bottom:0">
<p style="margin:0;background:url(https://h.online-metrix.net/fp/clear.png?{$urlQuery}&session2={$this->session}&m=1)"></p>
<img src="https://h.online-metrix.net/fp/clear.png?{$urlQuery}&m=2"/>
<script src="https://h.online-metrix.net/fp/check.js?{$urlQuery}"></script>
<object type="application/x-shockwave-flash" data="https://h.online-metrix.net/fp/fp.swf?{$urlQuery}" width="1" height="1" id="thm_fp">
<param name="movie" value="https://h.online-metrix.net/fp/fp.swf?{$urlQuery}" />
</object>
</div>
TRACKING;
    }

    public function getTrackingUrl()
    {
        $urlQuery = $this->getUrlQuery();

        return "https://h.online-metrix.net/fp/clear.png?{$urlQuery}&m=2";
    }
}
