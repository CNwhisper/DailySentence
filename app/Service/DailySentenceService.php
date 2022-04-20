<?php

namespace App\Service;

use GuzzleHttp\Client;

class DailySentenceService
{
    public $client;
    public $endPoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
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
    public function getSentence()
    {
        try {
            $response = $this->client->get($this->endPoint);
            if ($response->getStatusCode() == 200) {
                return response()->json($response->getBody()->__toString(), $response->getStatusCode());
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            } else if ($e->getCode() === 404) {
                return response()->json($e->getCode().' Not Found', $e->getCode());
            } else if ($e->getCode() === 500) {
                return response()->json($e->getCode().' Server Error', $e->getCode());
            } else {
                return response()->json('Bad Response: Code '. $e->getCode(), $e->getCode());
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return response()->json('Connect Exception: Code '. $e->getCode(), $e->getCode());
        } catch (\Exception $e) {
            return response()->json('Unknown Error.');
        }
    }



}
