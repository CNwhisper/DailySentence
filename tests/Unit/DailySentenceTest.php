<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use App\Service\DailySentenceService;

class DailySentenceTest extends TestCase
{
    private $dailySentenceService;

    private function setProperty()
    {
        $this->dailySentenceService->setEndPoint('http://metaphorpsum.com/sentences/3');
    }

    private function runDailySentence($mock) 
    {
        $handlerStack = HandlerStack::create($mock);
        $this->dailySentenceService->client = new Client(['handler' => $handlerStack]);
        
        return $this->dailySentenceService->getSentence();
    }

    protected function setUp() :void
    {

        $this->dailySentenceService = app(DailySentenceService::class);
        $this->setProperty();
        return;
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testDailySentence200()
    {
        $success = 'Success, I am Daily Sentence.';
        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'], $success),]);
        $result = $this->runDailySentence($mock);
        $this->assertEquals($success, $result);
    }

    public function testDailySentence404()
    {
        $notFound = '';
        $mock = new MockHandler([new Response(404, ['X-Foo' => 'Bar'], $notFound),]);
        $result = $this->runDailySentence($mock);
        $this->assertEquals('Error 404: Not Found', $result);
    }

    public function testDailySentence500()
    {
        $notFound = '';
        $mock = new MockHandler([new Response(500, ['X-Foo' => 'Bar'], $notFound),]);
        $result = $this->runDailySentence($mock);
        $this->assertEquals('Error 500: Server Error', $result);
    }

    public function testDailySentenceTimeOut()
    {
        $mock = new MockHandler([new \GuzzleHttp\Exception\ConnectException(
            'Time Out',
            new Request('get', '/')
        )]);

        $result = $this->runDailySentence($mock);
        $this->assertEquals('Error: Connect Exception.', $result);
    }

    public function testDailySentenceUnknown()
    {
        $mock = new MockHandler([new \Exception()]);
        $result = $this->runDailySentence($mock);
        $this->assertEquals('Error: Unknown.', $result);
    }
}
