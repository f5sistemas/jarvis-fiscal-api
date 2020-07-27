<?php

use PHPUnit\Framework\TestCase;

/**
 * Class JarvisFiscalTestCase
 */
class JarvisFiscalTestCase extends TestCase {

    /**
     * @var JarvisFiscalAPI
     */
    private $api;

    /**
     * @var JarvisFiscalCommonAPI
     */
    private $api_common;

    /**
     *
     */
    public function setUp() {

        $api_token = getenv('JARVIS_FISCAL_API_TOKEN');
        $api_token_common = getenv('JARVIS_FISCAL_COMMON_API_TOKEN');

        $this->api = new \F5Software\JarvisFiscal\JarvisFiscalAPI($api_token);
        $this->api_common = new \F5Software\JarvisFiscal\JarvisFiscalCommonAPI($api_token_common);

    }

    /**
     *
     */
    public function testDFeRecebidas() {

        $date_1 = (new \Carbon\Carbon())->addMonth(-1);
        $date_2 = new \Carbon\Carbon();

        $response = json_decode($this->api->getRecebidas($date_1, $date_2));
        $this->assertTrue($response->status_code == 200);

    }

    /**
     *
     */
    public function testDFeDownload() {

        $chave = '42200707777293000167550010000080911327789539';

        $response = json_decode($this->api_common->download($chave));
        $this->assertTrue($response->status_code == 200);

    }

}