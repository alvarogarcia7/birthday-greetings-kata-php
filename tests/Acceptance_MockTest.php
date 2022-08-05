<?php

declare(strict_types=1);

namespace Tests\BirthdayGreetingsKata;

use BirthdayGreetingsKata\BirthdayService;
use BirthdayGreetingsKata\XDate;
use PHPUnit\Framework\TestCase;

class AcceptanceMockTest extends TestCase
{
    /**
     * @var BirthdayService
     */
    private $service;

    /** @before */
    protected function startMailhog(): void
    {
        $this->service = new BirthdayServiceMocked();
    }

    /**
     * @test
     */
    public function willSendGreetings_whenItsSomebodysBirthday(): void
    {
        $this->service->sendGreetings(
            __DIR__ . '/resources/employee_data.txt',
            new XDate('2008/10/08'),
            '',
            ''
        );

        $messages = $this->service->messages;
        $this->assertCount(1, $messages, 'message not sent?');

        $message = $this->service->messages[0];
        $this->assertEquals('Happy Birthday, dear John!', $message['body']);
        $this->assertEquals('Happy Birthday!', $message['subject']);
        $this->assertEquals('john.doe@foobar.com', $message['recipient']);
        $this->assertEquals('sender@here.com', $message['sender']);
    }

}
