<?php

declare(strict_types=1);

namespace BirthdayGreetingsKata;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mailer
{
    private Swift_Mailer $mailer;

    public function __construct($smtpHost, $smtpPort)
    {
        $this->mailer = new Swift_Mailer(
            new Swift_SmtpTransport($smtpHost, $smtpPort)
        );
    }

    public function send($message)
    {
        $this->mailer->send($message);
    }
}

class BirthdayService
{
    private Mailer $mailer;
    private CSVReader $csvReader;

    public function sendGreetings($fileName, XDate $xDate, $smtpHost, $smtpPort): void
    {
        $this->mailer = new Mailer($smtpHost, $smtpPort);

        $this->csvReader = new CSVReader($fileName);

        $employees = [];

        while ($employeeData = $this->csvReader->nextOrNull()) {
            $employees[] = $employeeData;
        }

        foreach ($employees as $employeeData) {
            $employeeData = array_map('trim', $employeeData);
            $employee = new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]);
            if ($employee->isBirthday($xDate)) {
                $recipient = $employee->getEmail();
                $body = sprintf('Happy Birthday, dear %s!', $employee->getFirstName());
                $subject = 'Happy Birthday!';
                $this->sendMessage($smtpHost, $smtpPort, 'sender@here.com', $subject, $body, $recipient);
            }
        }
    }

    protected function sendMessage($smtpHost, $smtpPort, $sender, $subject, $body, $recipient): void
    {
        $msg = new Swift_Message($subject);
        $msg
            ->setFrom($sender)
            ->setTo([$recipient])
            ->setBody($body);
        $this->mailer->send($msg);
    }
}
