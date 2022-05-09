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

        $date_1 = new DateTime('2020-09-01');
        $date_2 = new DateTime('2020-09-30');

        $response = json_decode($this->api->getRecebidas($date_1, $date_2));
        $this->assertTrue($response->status_code == 200);

    }

    /**
     *
     */
    public function testDFeRecebidasByIds() {

        $ids = ['208578', '216907'];

        $response = json_decode($this->api->getRecebidas(null, null, $ids));
        $this->assertTrue($response->status_code == 200);

    }

    /**
     *
     */
    public function testMonthlyReport() {

        $date_1 = new DateTime('2020-09-01');
        $emails = [
            'email1@test.com.br',
            'email2@test.com.br'
        ];

        $response = json_decode($this->api->monthlyReport($date_1, $emails));
        $this->assertTrue($response->status_code == 200);

    }

    /**
     *
     */
    public function testLogMonthlyReport() {

        $response = json_decode($this->api->logMonthlyReport());
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

    /**
     *
     */
    public function testManifesto() {

        $chave = '42200707777293000167550010000080911327789539';

        $response = json_decode($this->api->manifesto($chave, '210200', null));
        $this->assertTrue($response->status_code == 200);

    }

    /**
     *
     */
    public function testCompaniesCertified() {

        $response = json_decode($this->api->getCertified());
        $this->assertIsString($response->file);

    }

}