<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\DailySentenceService;

class SentenceController extends Controller
{
    /**
     * get Daily Sentence
     * @param string $sourceName
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $sourceName = $request->input('sourceName', 'Metaphorpsum');

        $dailySentenceService = app(DailySentenceService::class);
        $dailySentenceService->setEndPoint($sourceName);
        $response = $dailySentenceService->getSentence();
        return response()->json($response, $dailySentenceService->statusCode);
    }
}
