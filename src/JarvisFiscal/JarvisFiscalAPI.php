<?php

namespace F5Software\JarvisFiscal;

use DateTime;

use F5Software\Body\Certified;
use F5Software\Body\EmitResume;
use F5Software\Body\NFeResume;

use GuzzleHttp\Client;

use JsonMapper;

/**
 * Class RequestAPI
 */
class JarvisFiscalAPI {

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
    const DFE_MANIFESTO = '/api/dfe/manifesto';

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
     * @const string
     */
    const RESUME_PERIOD = '/api/companies/resume';

    /**
     * @var string
     */
    protected $api_token;

    /**
     * @var string
     */
    protected $ambiente;

    /**
     * @var JsonMapper
     */
    protected $jsonMapper;

    /**
     * RequestAPI constructor.
     * @param string $api_token
     * @param string $ambiente
     */
    public function __construct(string $api_token, string $ambiente = '1') {

        $this->api_token = $api_token;
        $this->ambiente = $ambiente;

        $this->jsonMapper = new JsonMapper();
        $this->jsonMapper->bStrictNullTypes = false;

        $this->client = new Client([
            'base_uri' => $this->getHost(),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$api_token}"
            ]
        ]);

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
     * @param DateTime|null $begin
     * @param DateTime|null $end
     * @param array $ids
     * @return NFeResume[]
     * @throws \Exception
     */
    public function getRecebidas(DateTime $begin = null, DateTime $end = null, array $ids = []) {

        $data = [
            'dh_sai_ent_begin' => $begin ? $begin->format('Y-m-d') : null,
            'dh_sai_ent_end' => $end ? $end->format('Y-m-d') : null,
            'ids' => $ids,
        ];

        $response = $this->client->get(self::DFE_RECEBIDAS_GET_END_POINT, [
           'query' => $data
        ]);

        $response = json_decode($response->getBody()->getContents())->data;

        $result = [];

        foreach ($response as $item) {
            $result[] = $this->jsonMapper->map($item, new NFeResume());
        }

        return $result;

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
        ];

        $response = $this->client->post(self::COMPANIES_MONTHLY_FILES, [
            'json' => $data
        ]);

        return $response->getBody()->getContents();

    }

    /**
     * @return Certified
     * @throws \Exception
     */
    public function getCertified() {

        $response = $this->client->post(self::COMPANIES_CERTIFIED);

        $data = json_decode($response->getBody()->getContents());

        return $this->jsonMapper->map($data, new Certified());

    }

    /**
     * @return string
     */
    public function logMonthlyReport() {

        $response = $this->client->get(self::COMPANIES_LOG_MONTHLY_FILES);

        return $response->getBody()->getContents();

    }

    /**
     * @param string $chave
     * @param string $evento
     * @param string|null $justificativa
     * @return string
     */
    public function manifesto(string $chave, string $evento, string $justificativa = null) {

        $data = [
            'chave' => $chave,
            'evento' => $evento,
            'justificativa' => $justificativa
        ];

        $response = $this->client->post(self::DFE_MANIFESTO, [
            'json' => $data
        ]);

        return $response->getBody()->getContents();

    }

    /**
     * @param DateTime $period
     * @return EmitResume
     * @throws \Exception
     */
    public function resume(DateTime $period) {

        $data = [
            'period' => $period->format("Y-m"),
        ];

        $response = $this->client->get(self::RESUME_PERIOD, [
            'query' => $data
        ]);

        $data = json_decode($response->getBody()->getContents())->data;

        return $this->jsonMapper->map($data, new EmitResume());

    }

}