<?php

namespace App\Service;

use GuzzleHttp\Client;

class DailySentenceService
{
    public $statusCode;
    public $client;
    public $endPoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->statusCode = 200;
        $this->endPoint = '';
    }

    /**
     * set EndPoint
     * @param string $sourceName
     *
     * @return void
     */
    public function setEndPoint(String $sourceName) : void
    {
        $endpoints = config('endpoint.daily_sentence');
        if (isset($endpoints[$sourceName])) {
            $this->endPoint = $endpoints[$sourceName];
        }
    }

    /**
     * get A Sentence
     *
     * @return string
     */
    public function getSentence() : string
    {
        if (empty($this->endPoint)) {
            $sourceNames = implode(', ', array_keys(config('endpoint.daily_sentence')));
            return 'Warning, Invalid sourceNames! [Hints]: ' . $sourceNames;
        }

        try {
            $response = $this->client->get($this->endPoint);
            if ($response->getStatusCode() == 200) {
                $this->statusCode = $response->getStatusCode();
                return $response->getBody()->__toString();
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $this->statusCode = $e->getCode();
            $errMsg = 'Error ' . $e->getCode() . ': ';
            if ($e->getCode() === 502) {
                return $errMsg . 'Bad Gateway.';
            } else if ($e->getCode() === 401) {
                return $errMsg . 'Your credentials are incorrect. Please try again';
            } else if ($e->getCode() === 404) {
                return $errMsg . 'Not Found';
            } else if ($e->getCode() === 500) {
                return $errMsg . 'Server Error';
            } else {
                return $errMsg . 'Bad Response';
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->statusCode = $e->getCode();
            $errMsg = 'Error ' . $e->getCode() . ': ';
            return $errMsg . 'Connect Exception.';
        } catch (\Exception $e) {
            return 'Error: Unknown.';
        }
    }



}
