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
        $this->statusCode = 0;
    }

    public function setEndPoint(String $endPoint) : void
    {
        $this->endPoint = $endPoint;
    }

    /**
     * Display a listing of the resource.
     *
     * @return
     */
    public function getSentence() : string
    {
        try {
            $response = $this->client->get($this->endPoint);
            if ($response->getStatusCode() == 200) {
                $this->statusCode = $response->getStatusCode();
                return $response->getBody()->__toString();
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $this->statusCode = $e->getCode();
            $errMsg = 'Error ' . $e->getCode();
            if ($e->getCode() === 400) {
                return $errMsg . ': Invalid Request. Please enter a username or a password.';
            } else if ($e->getCode() === 401) {
                return $errMsg . ': Your credentials are incorrect. Please try again';
            } else if ($e->getCode() === 404) {
                return $errMsg . ': Not Found';
            } else if ($e->getCode() === 500) {
                return $errMsg . ': Server Error';
            } else {
                return $errMsg . ': Bad Response';
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->statusCode = $e->getCode();
            return 'Error: Connect Exception.';
        } catch (\Exception $e) {
            return 'Error: Unknown.';
        }
    }



}
