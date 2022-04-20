<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\DailySentenceService;

class SentenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $dailySentenceService = app(DailySentenceService::class);
        $dailySentenceService->setEndPoint('http://metaphorpsum.com/sentences/3');
        $response = $dailySentenceService->getSentence();
        return response()->json($response, $dailySentenceService->statusCode);
    }
}
