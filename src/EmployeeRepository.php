<?php

namespace BirthdayGreetingsKata;

class EmployeeRepository
{

    private CSVReader $csvReader;

    public function __construct(CSVReader $csvReader)
    {
        $this->csvReader = $csvReader;
    }

    public function nextOrNull(): ?Employee
    {
        $employeeData = $this->csvReader->nextOrNull();
        return $employeeData ? new Employee($employeeData[1], $employeeData[0], $employeeData[2], $employeeData[3]) : null;
    }
}