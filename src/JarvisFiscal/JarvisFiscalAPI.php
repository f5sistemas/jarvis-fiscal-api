<?php

namespace F5Software\JarvisFiscal;

use Carbon\Carbon;

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
     * @param Carbon|null $begin
     * @param Carbon|null $end
     * @return string
     */
    public function getRecebidas(Carbon $begin = null, Carbon $end = null) {

        $data = [
            'dh_sai_ent_begin' => $begin ? $begin->format('Y-m-d') : null,
            'dh_sai_ent_end' => $end ? $end->format('Y-m-d') : null,
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



}