<?php

namespace F5Software\JarvisFiscal;

use DateTime;

/**
 * Class RequestAPI
 */
class JarvisFiscalAPI {

    /**
     * @var string
     */
    protected $api_token;

    /**
     * @var string
     */
    protected $ambiente;

    /**
     * RequestAPI constructor.
     * @param string $api_token
     * @param string $ambiente
     */
    public function __construct($api_token, $ambiente = '1') {

        $this->api_token = $api_token;
        $this->ambiente = $ambiente;

    }

    /**
     * @return string
     */
    protected function getHost() {

        return $this->ambiente == '1' ?
            "http://fiscal.f5-jarvis.com.br" :
            "http://localhost:82";

    }

    /**
     * @const string
     */
    const DFE_RECEBIDAS_GET_END_POINT = '/api/dfe/recebidas';

    /**
     * @const string
     */
    const DFE_DOWNLOAD_END_POINT = '/api/dfe/download';

    /**
     * @const string
     */
    const COMPANIES_CERTIFIED = '/api/companies/certificado';

    /**
     * @const string
     */
    const COMPANIES_MONTHLY_FILES = '/api/companies/monthly-report';

    /**
     * @const string
     */
    const COMPANIES_LOG_MONTHLY_FILES = '/api/companies/monthly-report-log';

    /**
     * @param DateTime|null $begin
     * @param DateTime|null $end
     * @param array $ids
     * @return string
     */
    public function getRecebidas(DateTime $begin = null, DateTime $end = null, array $ids = []) {

        $data = [
            'dh_sai_ent_begin' => $begin ? $begin->format('Y-m-d') : null,
            'dh_sai_ent_end' => $end ? $end->format('Y-m-d') : null,
            'ids' => $ids,
            'api_token' => $this->api_token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->get(
            self::getHost() . self::DFE_RECEBIDAS_GET_END_POINT, [
                'query' => $data,
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $response = $response->getBody()->getContents();

        return $response;

    }

    /**
     * @param DateTime|null $date
     * @param array $emails
     * @return string
     */
    public function monthlyReport(DateTime $date, array $emails) {

        $data = [
            'date' => $date->format('Y-m-d'),
            'emails' => $emails,
            'api_token' => $this->api_token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            self::getHost() . self::COMPANIES_MONTHLY_FILES, [
                'query' => $data,
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $response = $response->getBody()->getContents();

        return $response;

    }

    /**
     * @return string
     */
    public function getCertified() {

        $data = [
            'api_token' => $this->api_token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->post(
            self::getHost() . self::COMPANIES_CERTIFIED, [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $response = $response->getBody()->getContents();

        return $response;

    }

    /**
     * @return string
     */
    public function logMonthlyReport() {

        $data = [
            'api_token' => $this->api_token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->get(
            self::getHost() . self::COMPANIES_LOG_MONTHLY_FILES, [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $response = $response->getBody()->getContents();

        return $response;

    }

}