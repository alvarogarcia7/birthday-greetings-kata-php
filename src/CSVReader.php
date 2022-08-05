<?php

namespace BirthdayGreetingsKata;

class CSVReader
{

    public function __construct($fileName)
    {
        $this->fileHandler = fopen($fileName, 'r');
        fgetcsv($this->fileHandler);
    }

    public function nextOrNull(): ?array
    {
        if ($employeeData = fgetcsv($this->fileHandler, null, ',')) {
            return $employeeData;
        }
        return null;
    }
}