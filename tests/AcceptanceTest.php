<?php

declare(strict_types=1);

namespace Tests\BirthdayGreetingsKata;

use BirthdayGreetingsKata\BirthdayService;
use BirthdayGreetingsKata\XDate;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class AcceptanceTest extends TestCase
{
    private const SMTP_HOST = 'mailhog';
    private const SMTP_PORT = 1025;

    /**
     * @var BirthdayService
     */
    private $service;

    /** @before */
    protected function startMailhog(): void
    {
        $this->service = new BirthdayService();
    }

    /** @after */
    protected function stopMailhog(): void
    {
        (new Client())->delete('http://mailhog:8025/api/v1/messages');
    }

    /**
     * @test
     */
    public function willSendGreetings_whenItsSomebodysBirthday(): void
    {
        $this->service->sendGreetings(
            __DIR__ . '/resources/employee_data_case1.txt',
            new XDate('2008/10/08'),
            static::SMTP_HOST,
            static::SMTP_PORT
        );

        $messages = $this->messagesSent();
        $this->assertCount(1, $messages, 'message not sent?');

        $message = $messages[0];
        $this->assertEquals('Happy Birthday, dear John!', $message['Content']['Body']);
        $this->assertEquals('Happy Birthday!', $message['Content']['Headers']['Subject'][0]);
        $this->assertCount(1, $message['Content']['Headers']['To']);
        $this->assertEquals('john.doe@foobar.com', $message['Content']['Headers']['To'][0]);
    }

    /**
     * @test
     */
    public function willSendMultipleGreetings_whenItsSomebodysBirthday(): void
    {
        $this->service->sendGreetings(
            __DIR__ . '/resources/employee_data_case2.txt',
            new XDate('2008/10/08'),
            static::SMTP_HOST,
            static::SMTP_PORT
        );

        $messages = $this->messagesSent();
        $this->assertCount(2, $messages, 'message not sent?');


        $message = $messages[0];
        $this->assertEquals('Happy Birthday, dear John!', $message['Content']['Body']);
        $this->assertEquals('Happy Birthday!', $message['Content']['Headers']['Subject'][0]);
        $this->assertCount(1, $message['Content']['Headers']['To']);
        $this->assertEquals('john.doe@foobar.com', $message['Content']['Headers']['To'][0]);

        $message = $messages[1];
        $this->assertEquals('Happy Birthday, dear John!', $message['Content']['Body']);
        $this->assertEquals('Happy Birthday!', $message['Content']['Headers']['Subject'][0]);
        $this->assertCount(1, $message['Content']['Headers']['To']);
        $this->assertEquals('john.bboy@foobar.com', $message['Content']['Headers']['To'][0]);
    }

    /**
     * @test
     */
    public function willNotSendEmailsWhenNobodysBirthday(): void
    {
        $this->service->sendGreetings(
            __DIR__ . '/resources/employee_data_case2.txt',
            new XDate('2008/01/01'),
            static::SMTP_HOST,
            static::SMTP_PORT
        );

        $this->assertCount(0, $this->messagesSent(), 'what? messages?');
    }

    private function messagesSent(): array
    {
        return json_decode(file_get_contents('http://mailhog:8025/api/v1/messages'), true);
    }
}
