<?php

namespace Tests\BirthdayGreetingsKata;

use BirthdayGreetingsKata\BirthdayService;

class BirthdayServiceMocked extends BirthdayService
{
    public $messages = [];

    protected function sendMessage($smtpHost, $smtpPort, $sender, $subject, $body, $recipient): void
    {
        $this->messages[] = ['host' => $smtpHost, 'port' => $smtpPort, 'subject'=> $subject, 'sender' => $sender, 'body' => $body, 'recipient' => $recipient];
    }

}