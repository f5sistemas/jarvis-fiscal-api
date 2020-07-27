<?php


namespace F5Software\JarvisFiscal;

/**
 * Class JarvisFiscalCommonAPI
 * @package F5Software\JarvisFiscal
 */
class JarvisFiscalCommonAPI {

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
    const DOWNLOAD_XML_END_POINT = '/api-common/xml/download';

    /**
     * @param $chave
     * @return string
     */
    public function download($chave) {

        $data = [
            'chave' => $chave,
            'api_token' => $this->api_token
        ];

        $client = new \GuzzleHttp\Client();
        $response = $client->get(
            self::getHost() . self::DOWNLOAD_XML_END_POINT, [
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