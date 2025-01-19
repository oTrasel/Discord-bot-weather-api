<?php
namespace App\WebService;

class OpenWeatherMap
{
    /**
     * URL base das APIs
     * @var string
     */
    const BASE_URL = "https://api.openweathermap.org";

    /**
     * Chave de acesso à API
     * @var string
     */
    private $apiKey;

    /**
     * Construtor da classe
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Consulta o clima atual de uma cidade Brasileira
     * @param string $cidade
     * @param string $uf
     * @return array
     */
    public function consultarClimaAtualBR($cidade, $uf)
    {
        return $this->get("/data/2.5/weather", [
            'q' => $cidade . ',BR-'. $uf . ',BRA'
        ]);
    }

    /**
     * Consulta o clima atual de uma cidade
     * @param string $resource
     * @param array $params
     * @return array
     */
    private function get($resource, $params){
        //PARAMETROS ADICIONAIS
        $params['units'] = 'metric';
        $params['lang'] = 'pt_br';
        $params['appid'] = $this->apiKey;

        $endpoint = self::BASE_URL . $resource . '?' . http_build_query($params);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'	
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}